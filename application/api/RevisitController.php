<?php
// File: api/RevisitController.php

class RevisitController {
    private $db;
    private $emailService;
    
    public function __construct($db) {
        $this->db = $db;
        $this->emailService = new EmailService($db);
    }
    
    /**
     * Handle QR code scanning for revisits
     */
    public function scanQRCode($request) {
        try {
            $qr_code = $request['qr_code'] ?? '';
            
            if (empty($qr_code)) {
                return $this->jsonResponse(['success' => false, 'message' => 'QR code is required'], 400);
            }
            
            // Look up QR code
            $stmt = $this->db->prepare("
                SELECT 
                    vqc.*,
                    v.*,
                    (SELECT COUNT(*) FROM visits WHERE visitor_id = v.visitor_id) as total_visits
                FROM visitor_qr_codes vqc
                JOIN visitors v ON vqc.visitor_id = v.visitor_id
                WHERE vqc.qr_code = ? AND vqc.is_active = 1
            ");
            
            $stmt->execute([$qr_code]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return $this->jsonResponse(['success' => false, 'message' => 'Invalid or inactive QR code'], 404);
            }
            
            // Update QR code usage
            $updateStmt = $this->db->prepare("
                UPDATE visitor_qr_codes 
                SET total_visits = total_visits + 1, 
                    last_used = NOW() 
                WHERE qr_code = ?
            ");
            $updateStmt->execute([$qr_code]);
            
            // Calculate days since last visit
            $lastVisitDays = 'First Visit';
            if ($result['last_visit_date']) {
                $lastVisit = new DateTime($result['last_visit_date']);
                $today = new DateTime();
                $interval = $today->diff($lastVisit);
                $lastVisitDays = $interval->days;
            }
            
            return $this->jsonResponse([
                'success' => true,
                'visitor' => [
                    'visitor_id' => $result['visitor_id'],
                    'first_name' => $result['first_name'],
                    'last_name' => $result['last_name'],
                    'email' => $result['email'],
                    'phone' => $result['phone'],
                    'company' => $result['company'],
                    'visitor_type' => $result['visitor_type'],
                    'qr_code' => $result['qr_code']
                ],
                'stats' => [
                    'total_visits' => $result['total_visits'],
                    'qr_visits' => $result['total_visits'],
                    'last_visit_days' => $lastVisitDays
                ]
            ]);
            
        } catch (Exception $e) {
            return $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Look up visitor by email or phone
     */
    public function lookupVisitor($request) {
        try {
            $email = $request['email'] ?? '';
            $phone = $request['phone'] ?? '';
            
            if (empty($email) && empty($phone)) {
                return $this->jsonResponse(['success' => false, 'message' => 'Email or phone required'], 400);
            }
            
            // Build query based on provided fields
            $query = "
                SELECT 
                    v.*,
                    vqc.qr_code,
                    vqc.total_visits as qr_visits,
                    (SELECT COUNT(*) FROM visits WHERE visitor_id = v.visitor_id) as total_visits_count
                FROM visitors v
                LEFT JOIN visitor_qr_codes vqc ON v.visitor_id = vqc.visitor_id
                WHERE v.merged_with_visitor_id IS NULL
            ";
            
            $params = [];
            $conditions = [];
            
            if (!empty($email)) {
                $conditions[] = "v.email = ?";
                $params[] = $email;
            }
            
            if (!empty($phone)) {
                $conditions[] = "v.phone = ?";
                $params[] = $phone;
            }
            
            $query .= " AND (" . implode(" OR ", $conditions) . ")";
            $query .= " ORDER BY v.last_visit_date DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $visitors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($visitors) > 0) {
                // Check for potential duplicates
                $this->checkAndMergeDuplicates($visitors);
                
                return $this->jsonResponse([
                    'success' => true,
                    'visitors' => $visitors,
                    'message' => count($visitors) > 1 ? 'Multiple records found' : 'Record found'
                ]);
            } else {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'No visitor record found',
                    'visitors' => []
                ]);
            }
            
        } catch (Exception $e) {
            return $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Generate and send QR code for existing visitor
     */
    public function sendQRCode($request) {
        try {
            $visitor_id = $request['visitor_id'] ?? 0;
            
            if (empty($visitor_id)) {
                return $this->jsonResponse(['success' => false, 'message' => 'Visitor ID required'], 400);
            }
            
            // Check if QR code already exists
            $checkStmt = $this->db->prepare("
                SELECT qr_code FROM visitor_qr_codes WHERE visitor_id = ?
            ");
            $checkStmt->execute([$visitor_id]);
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                $qr_code = $existing['qr_code'];
            } else {
                // Generate new QR code
                $qr_code = $this->generateQRCode($visitor_id);
                
                // Store in database
                $insertStmt = $this->db->prepare("
                    INSERT INTO visitor_qr_codes (visitor_id, qr_code, first_issued, qr_image)
                    VALUES (?, ?, NOW(), ?)
                ");
                
                $qr_image = $this->generateQRImage($qr_code);
                $insertStmt->execute([$visitor_id, $qr_code, $qr_image]);
            }
            
            // Get visitor details
            $visitorStmt = $this->db->prepare("
                SELECT * FROM visitors WHERE visitor_id = ?
            ");
            $visitorStmt->execute([$visitor_id]);
            $visitor = $visitorStmt->fetch(PDO::FETCH_ASSOC);
            
            // Send email with QR code
            $emailSent = $this->emailService->sendQRCodeEmail($visitor, $qr_code);
            
            return $this->jsonResponse([
                'success' => true,
                'qr_code' => $qr_code,
                'email_sent' => $emailSent,
                'message' => $emailSent ? 'QR code sent to email' : 'QR code generated'
            ]);
            
        } catch (Exception $e) {
            return $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Handle checkout and generate QR code
     */
    public function checkoutWithQR($request) {
        try {
            $visit_id = $request['visit_id'] ?? 0;
            
            if (empty($visit_id)) {
                return $this->jsonResponse(['success' => false, 'message' => 'Visit ID required'], 400);
            }
            
            // Get visit details
            $visitStmt = $this->db->prepare("
                SELECT v.*, vi.* 
                FROM visits v
                JOIN visitors vi ON v.visitor_id = vi.visitor_id
                WHERE v.visit_id = ?
            ");
            $visitStmt->execute([$visit_id]);
            $visit = $visitStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$visit) {
                return $this->jsonResponse(['success' => false, 'message' => 'Visit not found'], 404);
            }
            
            // Update checkout time
            $checkoutStmt = $this->db->prepare("
                UPDATE visits SET check_out_time = NOW() WHERE visit_id = ?
            ");
            $checkoutStmt->execute([$visit_id]);
            
            // Generate or retrieve QR code
            $qrStmt = $this->db->prepare("
                SELECT qr_code FROM visitor_qr_codes WHERE visitor_id = ?
            ");
            $qrStmt->execute([$visit['visitor_id']]);
            $existingQR = $qrStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existingQR) {
                $qr_code = $existingQR['qr_code'];
            } else {
                $qr_code = $this->generateQRCode($visit['visitor_id']);
                
                // Store QR code
                $insertQR = $this->db->prepare("
                    INSERT INTO visitor_qr_codes (visitor_id, qr_code, first_issued, qr_image)
                    VALUES (?, ?, NOW(), ?)
                ");
                $qr_image = $this->generateQRImage($qr_code);
                $insertQR->execute([$visit['visitor_id'], $qr_code, $qr_image]);
            }
            
            // Send email with QR code
            $this->emailService->sendCheckoutEmail($visit, $qr_code);
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Checkout successful',
                'qr_code' => $qr_code,
                'visitor' => [
                    'name' => $visit['first_name'] . ' ' . $visit['last_name'],
                    'email' => $visit['email'],
                    'company' => $visit['company']
                ]
            ]);
            
        } catch (Exception $e) {
            return $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Handle delivery personnel quick registration
     */
    public function handleDeliveryQuickRegister($request) {
        try {
            $company = $request['company'] ?? '';
            $driver_name = $request['driver_name'] ?? '';
            $phone = $request['phone'] ?? '';
            
            // Check if company has a master QR code
            $companyQR = $this->getCompanyQRCode($company);
            
            if ($companyQR) {
                // Use company QR for all delivery personnel
                return $this->jsonResponse([
                    'success' => true,
                    'existing_qr' => true,
                    'company_qr' => $companyQR,
                    'message' => 'Company QR code available for all delivery personnel'
                ]);
            } else {
                // Create new delivery visitor record
                $stmt = $this->db->prepare("
                    INSERT INTO visitors (first_name, last_name, email, phone, company, visitor_type)
                    VALUES (?, '', ?, ?, ?, 'delivery')
                ");
                
                $email = strtolower(str_replace(' ', '', $company)) . '@delivery.temp';
                $stmt->execute([$driver_name, $email, $phone, $company]);
                $visitor_id = $this->db->lastInsertId();
                
                // Generate company QR code
                $qr_code = $this->generateDeliveryQRCode($company, $visitor_id);
                
                return $this->jsonResponse([
                    'success' => true,
                    'visitor_id' => $visitor_id,
                    'qr_code' => $qr_code,
                    'message' => 'Delivery personnel registered'
                ]);
            }
            
        } catch (Exception $e) {
            return $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Check and merge duplicate visitors
     */
    private function checkAndMergeDuplicates($visitors) {
        if (count($visitors) <= 1) return;
        
        // Group by email and phone
        $emailGroups = [];
        $phoneGroups = [];
        
        foreach ($visitors as $visitor) {
            if (!empty($visitor['email'])) {
                $emailGroups[$visitor['email']][] = $visitor['visitor_id'];
            }
            if (!empty($visitor['phone'])) {
                $phoneGroups[$visitor['phone']][] = $visitor['visitor_id'];
            }
        }
        
        // Find duplicates
        foreach ($emailGroups as $email => $ids) {
            if (count($ids) > 1) {
                $this->storeDuplicateInfo($email, null, $ids);
            }
        }
        
        foreach ($phoneGroups as $phone => $ids) {
            if (count($ids) > 1) {
                $this->storeDuplicateInfo(null, $phone, $ids);
            }
        }
    }
    
    /**
     * Store duplicate visitor information
     */
    private function storeDuplicateInfo($email, $phone, $visitor_ids) {
        try {
            $primary_id = min($visitor_ids); // Use oldest record as primary
            
            $stmt = $this->db->prepare("
                INSERT INTO visitor_email_verification 
                (email, phone, visitor_ids, primary_visitor_id, verification_status)
                VALUES (?, ?, ?, ?, 'pending')
                ON DUPLICATE KEY UPDATE
                visitor_ids = ?, updated_at = NOW()
            ");
            
            $ids_json = json_encode($visitor_ids);
            $stmt->execute([$email, $phone, $ids_json, $primary_id, $ids_json]);
            
        } catch (Exception $e) {
            // Log error but don't fail the request
            error_log("Duplicate storage error: " . $e->getMessage());
        }
    }
    
    /**
     * Generate unique QR code
     */
    private function generateQRCode($visitor_id) {
        $stmt = $this->db->prepare("CALL generate_visitor_qr_code(?, @qr_code)");
        $stmt->execute([$visitor_id]);
        
        $result = $this->db->query("SELECT @qr_code as qr_code")->fetch(PDO::FETCH_ASSOC);
        return $result['qr_code'];
    }
    
    /**
     * Generate QR code for delivery companies
     */
    private function generateDeliveryQRCode($company, $visitor_id) {
        $prefix = 'DLV';
        $code = $prefix . '-' . strtoupper(substr($company, 0, 3)) . '-' . date('Y') . '-' . str_pad($visitor_id, 4, '0', STR_PAD_LEFT);
        
        // Store in database
        $stmt = $this->db->prepare("
            INSERT INTO visitor_qr_codes (visitor_id, qr_code, first_issued, qr_type)
            VALUES (?, ?, NOW(), 'delivery')
        ");
        $stmt->execute([$visitor_id, $code]);
        
        return $code;
    }
    
    /**
     * Get company-wide QR code for delivery companies
     */
    private function getCompanyQRCode($company) {
        $stmt = $this->db->prepare("
            SELECT vqc.qr_code 
            FROM visitor_qr_codes vqc
            JOIN visitors v ON vqc.visitor_id = v.visitor_id
            WHERE v.company = ? 
              AND v.visitor_type = 'delivery' 
              AND vqc.qr_type = 'delivery'
            LIMIT 1
        ");
        $stmt->execute([$company]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['qr_code'] : null;
    }
    
    /**
     * Generate QR code image (base64)
     */
    private function generateQRImage($qr_code) {
        // This would use a QR code library like PHP QR Code
        // For now, returning placeholder
        return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==";
    }
    
    /**
     * JSON response helper
     */
    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}
?>