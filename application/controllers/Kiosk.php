<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kiosk extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
    }
    
    // Display the kiosk interface
    public function index() {
        $this->load->view('kiosk/index');
    }
    
    // Get departments and employees for the dropdown
    // public function get_departments() {
    //     $departments = $this->db->select('department_code, name')
    //                            ->from('departments')
    //                            ->order_by('name', 'ASC')
    //                            ->get()
    //                            ->result_array();
        
    //     echo json_encode(['status' => 'success', 'departments' => $departments]);
    // }
    
    // // Get departments and employees for the dropdown - WITH TRANSLATIONS
    // public function get_departments() {
    //     // SELECT all translation columns
    //     $departments = $this->db->select('department_code, name, 
    //                                     name_en, name_zh_tw, name_zh_cn, name_fil, name_ja')
    //                         ->from('departments')
    //                         ->order_by('name', 'ASC')
    //                         ->get()
    //                         ->result_array();
        
    //     echo json_encode(['status' => 'success', 'departments' => $departments]);
    // }
    // Get departments and employees for the dropdown - WITH TRANSLATIONS
    public function get_departments() {
        // SELECT all translation columns - ONLY ACTIVE DEPARTMENTS
        $departments = $this->db->select('department_code, name, 
                                        name_en, name_zh_tw, name_zh_cn, name_fil, name_ja')
                            ->from('departments')
                            ->where('is_active', 1)  // ADD THIS LINE
                            ->order_by('name', 'ASC')
                            ->get()
                            ->result_array();
        
        echo json_encode(['status' => 'success', 'departments' => $departments]);
    }
    
    // Get employees by department
    // public function get_employees($department_code = null) {
    //     if (!$department_code) {
    //         echo json_encode(['status' => 'error', 'message' => 'Department code required']);
    //         return;
    //     }
        
    //     $employees = $this->db->select('employee_id, name, email')
    //                          ->from('employees')
    //                          ->where('department_code', $department_code)
    //                          ->where('is_active', 1)
    //                          ->order_by('name', 'ASC')
    //                          ->get()
    //                          ->result_array();
        
    //     echo json_encode(['status' => 'success', 'employees' => $employees]);
    // }

    // Get employees by department - FILTERED BY COMPANY
    public function get_employees($department_code = null) {
        if (!$department_code) {
            echo json_encode(['status' => 'error', 'message' => 'Department code required']);
            return;
        }
        
        // Get company_visited parameter from query string
        $company_visited = $this->input->get('company_visited');
        
        // Validate company_visited parameter
        if (empty($company_visited)) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Company parameter is required'
            ]);
            return;
        }
        
        // Query employees that match the department AND (company OR "Both")
        $this->db->select('employee_id, name, email')
                ->from('employees')
                ->where('department_code', $department_code)
                ->where('is_active', 1)
                ->group_start()
                    ->where('company_owned_by', $company_visited)
                    ->or_where('company_owned_by', 'Both')
                ->group_end()
                ->order_by('name', 'ASC');
        
        $employees = $this->db->get()->result_array();
        
        echo json_encode(['status' => 'success', 'employees' => $employees]);
    }

    public function complete_checkin() {
        // SET TIMEZONE FIRST - This is the key fix!
        date_default_timezone_set('Asia/Manila');
        
        // Get JSON input
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!$data) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data received']);
            return;
        }
        
        // VALIDATE: At least one of email or phone must be provided
        $has_email = !empty($data['email']);
        $has_phone = !empty($data['phone']);
        
        if (!$has_email && !$has_phone) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'At least one contact method (email or phone) is required'
            ]);
            return;
        }
        
        // Get company_visited from request (defaults to 'Pan Asia' if not provided)
        $company_visited = isset($data['company_visited']) ? $data['company_visited'] : 'Pan Asia';
        
        // // ========================================
        // // NEW: CHECK FOR ACTIVE VISIT BEFORE ALLOWING CHECK-IN
        // // ========================================
        // $active_visit_check = "SELECT 
        //                         vis.visit_id,
        //                         vis.badge_number,
        //                         vis.check_in_time,
        //                         vis.valid_until,
        //                         vis.purpose,
        //                         v.visitor_id,
        //                         v.first_name,
        //                         v.last_name,
        //                         v.company,
        //                         e.name as host_name,
        //                         d.name as department
        //                     FROM visits vis
        //                     JOIN visitors v ON vis.visitor_id = v.visitor_id
        //                     JOIN employees e ON vis.host_employee_id = e.employee_id
        //                     JOIN departments d ON e.department_code = d.department_code
        //                     WHERE vis.check_out_time IS NULL
        //                     AND vis.company_visited = ?
        //                     AND (";
        
        // $params = array($company_visited);
        // $conditions = array();
        
        // if ($has_email) {
        //     $conditions[] = "LOWER(v.email) = ?";
        //     $params[] = strtolower($data['email']);
        // }
        
        // if ($has_phone) {
        //     $conditions[] = "v.phone = ?";
        //     $params[] = $data['phone'];
        // }
        
        // $active_visit_check .= implode(' OR ', $conditions) . ")
        //                     ORDER BY vis.check_in_time DESC
        //                     LIMIT 1";
        
        // $active_visit_result = $this->db->query($active_visit_check, $params);
        
        // if ($active_visit_result->num_rows() > 0) {
        //     $active_visit = $active_visit_result->row_array();
            
        //     // Visitor already has an active visit - DENY check-in
        //     echo json_encode([
        //         'status' => 'error',
        //         'message' => 'You are already checked in at the premises',
        //         'has_active_visit' => true,
        //         'active_visit' => [
        //             'visit_id' => $active_visit['visit_id'],
        //             'badge_number' => $active_visit['badge_number'],
        //             'visitor_name' => $active_visit['first_name'] . ' ' . $active_visit['last_name'],
        //             'company' => $active_visit['company'],
        //             'host_name' => $active_visit['host_name'],
        //             'department' => $active_visit['department'],
        //             'purpose' => $active_visit['purpose'],
        //             'check_in_time' => $active_visit['check_in_time'],
        //             'valid_until' => $active_visit['valid_until']
        //         ]
        //     ]);
        //     return;
        // }
        // // ========================================
        // // END: ACTIVE VISIT CHECK
        // // ========================================

        // Start transaction
        $this->db->trans_start();
        
        // Check if visitor already exists by email OR phone
        $existing_visitor = null;
        
        if ($has_email) {
            // Try to find by email first
            $existing_visitor = $this->db->get_where('visitors', ['email' => $data['email']])->row();
        }
        
        // If not found by email and phone is provided, try phone
        if (!$existing_visitor && $has_phone) {
            $existing_visitor = $this->db->get_where('visitors', ['phone' => $data['phone']])->row();
        }
        
        if ($existing_visitor) {
            $visitor_id = $existing_visitor->visitor_id;
            
            // Update visitor info if needed
            $visitor_update = [
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'email' => $has_email ? $data['email'] : null,
                'phone' => $has_phone ? $data['phone'] : null,
                'company' => $data['company'],
                'visitor_type' => $data['type'],
                'company_visited' => $company_visited,
                'updated_at' => date('Y-m-d H:i:s')  // Now uses Asia/Manila timezone
            ];
            
            // Update photo only if new one is provided
            if (!empty($data['photo'])) {
                $visitor_update['photo'] = $data['photo'];
            }
            
            $this->db->where('visitor_id', $visitor_id);
            $this->db->update('visitors', $visitor_update);
        } else {
            // Create new visitor
            $visitor_data = [
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'email' => $has_email ? $data['email'] : null,
                'phone' => $has_phone ? $data['phone'] : null,
                'company' => $data['company'],
                'photo' => isset($data['photo']) ? $data['photo'] : null,
                'visitor_type' => $data['type'],
                'company_visited' => $company_visited,
                'created_at' => date('Y-m-d H:i:s')  // Now uses Asia/Manila timezone
            ];
            
            $this->db->insert('visitors', $visitor_data);
            $visitor_id = $this->db->insert_id();
        }
        
        // Create visit record
        // Use client-provided time if available, otherwise use server time (now in correct timezone)
        $check_in_time = isset($data['check_in_time']) && !empty($data['check_in_time']) 
            ? $data['check_in_time'] 
            : date('Y-m-d H:i:s');
        
        // Calculate valid_until as 8 hours from check_in_time
        $valid_until = date('Y-m-d H:i:s', strtotime($check_in_time . ' +8 hours'));
        
        $visit_data = [
            'visitor_id' => $visitor_id,
            'host_employee_id' => $data['host']['id'],
            'purpose' => $data['purpose'],
            'additional_notes' => isset($data['notes']) ? $data['notes'] : null,
            'check_in_time' => $check_in_time,  // Now uses Asia/Manila timezone
            'valid_until' => $valid_until,      // Now uses Asia/Manila timezone
            'terms_accepted' => 1,
            'photo_consent' => 1,
            'company_visited' => $company_visited
        ];
        
        $this->db->insert('visits', $visit_data);
        $visit_id = $this->db->insert_id();
        
        // Get the generated badge number
        $visit = $this->db->get_where('visits', ['visit_id' => $visit_id])->row();
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
            return;
        }
        
        // Log for debugging (optional - remove in production)
        log_message('info', 'Check-in completed at: ' . $check_in_time . ' (Asia/Manila)');
        
        // Return success response
        echo json_encode([
            'status' => 'success',
            'data' => [
                'visit_id' => $visit_id,
                'visitor_id' => $visitor_id,
                'badge_number' => $visit->badge_number,
                'visitor_name' => $data['firstName'] . ' ' . $data['lastName'],
                'company' => $data['company'],
                'host_name' => $data['host']['name'],
                'check_in_time' => $check_in_time,  // Send back for verification
                'valid_until' => $valid_until,
                'company_visited' => $company_visited
            ]
        ]);
    }

    public function check_duplicate_visitor() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $email = $input['email'] ?? null;
        $phone = $input['phone'] ?? null;
        
        // Search for existing visitor with matching email OR phone
        $this->db->group_start();
        if ($email) {
            $this->db->or_where('email', $email);
        }
        if ($phone) {
            $this->db->or_where('phone', $phone);
        }
        $this->db->group_end();
        
        $visitor = $this->db->get('visitors')->row_array();
        
        if ($visitor) {
            // Count total visits
            $total_visits = $this->db
                ->where('visitor_id', $visitor['visitor_id'])
                ->count_all_results('visits');
            
            $visitor['total_visits'] = $total_visits;
            
            echo json_encode([
                'status' => 'found',
                'visitor' => $visitor
            ]);
        } else {
            echo json_encode([
                'status' => 'not_found'
            ]);
        }
    }
    
    // // Complete check-in and insert visitor data
    // public function complete_checkin() {
    //     // Get JSON data from request
    //     $json = file_get_contents('php://input');
    //     $data = json_decode($json, true);
        
    //     if (!$data) {
    //         echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    //         return;
    //     }
        
    //     $this->db->trans_start();
        
    //     try {
    //         // Check if visitor exists
    //         $existing_visitor = $this->db->select('visitor_id')
    //                                     ->from('visitors')
    //                                     ->where('email', $data['email'])
    //                                     ->get()
    //                                     ->row();
            
    //         if ($existing_visitor) {
    //             // Update existing visitor
    //             $visitor_id = $existing_visitor->visitor_id;
                
    //             $visitor_update = [
    //                 'first_name' => $data['firstName'],
    //                 'last_name' => $data['lastName'],
    //                 'phone' => $data['phone'],
    //                 'company' => $data['company'],
    //                 'visitor_type' => $data['type'] ?? 'new',
    //                 'updated_at' => date('Y-m-d H:i:s')
    //             ];
                
    //             // Update photo if provided
    //             if (isset($data['photo']) && !empty($data['photo'])) {
    //                 $visitor_update['photo'] = $data['photo'];
    //             }
                
    //             $this->db->where('visitor_id', $visitor_id)
    //                     ->update('visitors', $visitor_update);
    //         } else {
    //             // Insert new visitor
    //             $visitor_data = [
    //                 'first_name' => $data['firstName'],
    //                 'last_name' => $data['lastName'],
    //                 'email' => $data['email'],
    //                 'phone' => $data['phone'],
    //                 'company' => $data['company'],
    //                 'photo' => $data['photo'] ?? null,
    //                 'visitor_type' => $data['type'] ?? 'new',
    //                 'created_at' => date('Y-m-d H:i:s')
    //             ];
                
    //             $this->db->insert('visitors', $visitor_data);
    //             $visitor_id = $this->db->insert_id();
    //         }
            
    //         // Generate badge number using stored procedure
    //         $this->db->query("CALL generate_badge_number(@badge_number)");
    //         $badge_result = $this->db->query("SELECT @badge_number as badge_number")->row();
    //         $badge_number = $badge_result->badge_number;
            
    //         // Calculate valid_until (8 hours from check-in)
    //         $check_in_time = date('Y-m-d H:i:s');
    //         $valid_until = date('Y-m-d H:i:s', strtotime('+8 hours'));
            
    //         // Insert visit record
    //         $visit_data = [
    //             'visitor_id' => $visitor_id,
    //             'host_employee_id' => $data['host']['id'],
    //             'badge_number' => $badge_number,
    //             'purpose' => $data['purpose'],
    //             'additional_notes' => $data['notes'] ?? null,
    //             'check_in_time' => $check_in_time,
    //             'valid_until' => $valid_until,
    //             'terms_accepted' => 1,
    //             'photo_consent' => 1,
    //             'created_at' => date('Y-m-d H:i:s')
    //         ];
            
    //         $this->db->insert('visits', $visit_data);
    //         $visit_id = $this->db->insert_id();
            
    //         // Check if this is a pre-scheduled visit and update status
    //         if (isset($data['booking_code'])) {
    //             $this->db->where('booking_code', $data['booking_code'])
    //                     ->update('pre_scheduled_visits', [
    //                         'status' => 'checked_in',
    //                         'visit_id' => $visit_id
    //                     ]);
    //         }
            
    //         // Send notification email to host (optional)
    //         $this->send_host_notification($data, $badge_number);
            
    //         $this->db->trans_complete();
            
    //         if ($this->db->trans_status() === FALSE) {
    //             throw new Exception('Transaction failed');
    //         }
            
    //         // Return success response with badge details
    //         echo json_encode([
    //             'status' => 'success',
    //             'message' => 'Check-in completed successfully',
    //             'data' => [
    //                 'badge_number' => $badge_number,
    //                 'visit_id' => $visit_id,
    //                 'valid_until' => $valid_until,
    //                 'visitor_name' => $data['firstName'] . ' ' . $data['lastName'],
    //                 'company' => $data['company'],
    //                 'host_name' => $data['host']['name']
    //             ]
    //         ]);
            
    //     } catch (Exception $e) {
    //         $this->db->trans_rollback();
    //         echo json_encode([
    //             'status' => 'error',
    //             'message' => 'Check-in failed: ' . $e->getMessage()
    //         ]);
    //     }
    // }
    
    // // Search for returning visitors by QR code data
    // public function search_visitor() {
    //     $json = file_get_contents('php://input');
    //     $data = json_decode($json, true);
        
    //     if (!isset($data['email'])) {
    //         echo json_encode(['status' => 'error', 'message' => 'Email required']);
    //         return;
    //     }
        
    //     $visitor = $this->db->select('v.*, 
    //                                  (SELECT COUNT(*) FROM visits WHERE visitor_id = v.visitor_id) as total_visits')
    //                        ->from('visitors v')
    //                        ->where('email', $data['email'])
    //                        ->get()
    //                        ->row_array();
        
    //     if ($visitor) {
    //         echo json_encode(['status' => 'success', 'visitor' => $visitor]);
    //     } else {
    //         echo json_encode(['status' => 'not_found', 'message' => 'Visitor not found']);
    //     }
    // }

    // Add this method to your Kiosk Controller

    public function get_visitor_by_badge() {
        header('Content-Type: application/json');
        
        // Get JSON input
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!isset($data['badge_number']) || empty($data['badge_number'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Badge number is required'
            ]);
            return;
        }
        
        $badge_number = $data['badge_number'];
        
        try {
            // First, get the visitor_id from the most recent visit with this badge
            $visit_query = "SELECT visitor_id, badge_number 
                        FROM visits 
                        WHERE badge_number = ? 
                        ORDER BY check_in_time DESC 
                        LIMIT 1";
            
            $visit_result = $this->db->query($visit_query, array($badge_number));
            
            if ($visit_result->num_rows() == 0) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Badge number not found'
                ]);
                return;
            }
            
            $visit_row = $visit_result->row();
            $visitor_id = $visit_row->visitor_id;
            
            // ========================================
            // NEW: CHECK FOR ACTIVE VISIT
            // ========================================
            $active_visit_query = "SELECT 
                                    v.visit_id,
                                    v.badge_number,
                                    v.check_in_time,
                                    v.valid_until,
                                    v.purpose,
                                    e.name as host_name,
                                    d.name as department
                                FROM visits v
                                JOIN employees e ON v.host_employee_id = e.employee_id
                                JOIN departments d ON e.department_code = d.department_code
                                WHERE v.visitor_id = ? 
                                AND v.check_out_time IS NULL
                                ORDER BY v.check_in_time DESC
                                LIMIT 1";
            
            $active_visit_result = $this->db->query($active_visit_query, array($visitor_id));
            
            $has_active_visit = false;
            $active_visit_data = null;
            
            if ($active_visit_result->num_rows() > 0) {
                $has_active_visit = true;
                $active_visit_data = $active_visit_result->row_array();
            }
            // ========================================
            // END: ACTIVE VISIT CHECK
            // ========================================
            
            // Get visitor information
            $visitor_query = "SELECT 
                                v.visitor_id,
                                v.first_name,
                                v.last_name,
                                v.email,
                                v.phone,
                                v.company,
                                v.photo,
                                COUNT(vis.visit_id) as total_visits
                            FROM visitors v
                            LEFT JOIN visits vis ON v.visitor_id = vis.visitor_id
                            WHERE v.visitor_id = ?
                            GROUP BY v.visitor_id";
            
            $visitor_result = $this->db->query($visitor_query, array($visitor_id));
            
            if ($visitor_result->num_rows() > 0) {
                $visitor = $visitor_result->row_array();
                
                echo json_encode([
                    'status' => 'success',
                    'visitor' => $visitor,
                    'has_active_visit' => $has_active_visit,
                    'active_visit' => $active_visit_data
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Visitor information not found'
                ]);
            }
            
        } catch (Exception $e) {
            log_message('error', 'Error in get_visitor_by_badge: ' . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error occurred'
            ]);
        }
    }

    public function emergency_alert() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            return;
        }
        
        $visitor_name = $this->db->escape_str($input['visitor_name'] ?? 'Anonymous Visitor');
        $location = $this->db->escape_str($input['location'] ?? 'Kiosk Station');
        $company_visited = $this->db->escape_str($input['company_visited'] ?? 'Toms World');
        
        $sql = "INSERT INTO emergency_alerts (visitor_name, location, company_visited, created_at) 
                VALUES ('$visitor_name', '$location', '$company_visited', NOW())";
        
        if ($this->db->query($sql)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save alert']);
        }
    }
    
    // Get pre-scheduled visits
    public function get_prescheduled() {
        $search = $this->input->get('search');
        
        $this->db->select('ps.*, e.name as host_name, d.name as department')
                ->from('pre_scheduled_visits ps')
                ->join('employees e', 'ps.host_employee_id = e.employee_id')
                ->join('departments d', 'e.department_code = d.department_code')
                ->where('ps.status', 'scheduled')
                ->where('ps.scheduled_time >', date('Y-m-d H:i:s', strtotime('-1 day')));
        
        if ($search) {
            $this->db->group_start()
                    ->like('ps.booking_code', $search)
                    ->or_like('ps.visitor_name', $search)
                    ->group_end();
        }
        
        $visits = $this->db->order_by('ps.scheduled_time', 'ASC')
                          ->get()
                          ->result_array();
        
        echo json_encode(['status' => 'success', 'visits' => $visits]);
    }
    
    // Check out visitor
    public function checkout() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!isset($data['badge_number'])) {
            echo json_encode(['status' => 'error', 'message' => 'Badge number required']);
            return;
        }
        
        $result = $this->db->where('badge_number', $data['badge_number'])
                          ->where('check_out_time IS NULL', null, false)
                          ->update('visits', [
                              'check_out_time' => date('Y-m-d H:i:s')
                          ]);
        
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Checked out successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid badge or already checked out']);
        }
    }
    
    // Private function to send host notification
    private function send_host_notification($visitor_data, $badge_number) {
        // This is a placeholder for email notification
        // You can implement actual email sending using CodeIgniter's email library
        
        $host_email = $visitor_data['host']['email'] ?? '';
        $host_name = $visitor_data['host']['name'] ?? '';
        $visitor_name = $visitor_data['firstName'] . ' ' . $visitor_data['lastName'];
        
        // Log the notification for now
        log_message('info', "Visitor notification: $visitor_name has arrived to meet $host_name (Badge: $badge_number)");
        
        // Implement email sending if needed:
        // $this->load->library('email');
        // $this->email->from('noreply@company.com', 'Visitor Management System');
        // $this->email->to($host_email);
        // $this->email->subject('Visitor Arrival Notification');
        // $this->email->message("$visitor_name from {$visitor_data['company']} has arrived to meet you.");
        // $this->email->send();
    }
    
    // Get visitor statistics for dashboard
    public function get_stats() {
        $today = date('Y-m-d');
        
        $stats = [
            'today_checkins' => $this->db->where('DATE(check_in_time)', $today)
                                        ->count_all_results('visits'),
            
            'active_visitors' => $this->db->where('check_out_time IS NULL', null, false)
                                         ->where('valid_until >', date('Y-m-d H:i:s'))
                                         ->count_all_results('visits'),
            
            'total_visitors' => $this->db->count_all_results('visitors'),
            
            'scheduled_today' => $this->db->where('DATE(scheduled_time)', $today)
                                         ->where('status', 'scheduled')
                                         ->count_all_results('pre_scheduled_visits')
        ];
        
        echo json_encode(['status' => 'success', 'stats' => $stats]);
    }

    /**
     * Search for a visitor by email (for QR code returning visitors)
     */
    public function search_visitor()
    {
        // Set JSON header
        header('Content-Type: application/json');
        
        // Only accept POST requests
        if ($this->input->method() !== 'post') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
            return;
        }
        
        // Get JSON input
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!$data || (!isset($data['email']) && !isset($data['phone']))) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email or phone is required'
            ]);
            return;
        }
        
        // Try to find by email first if provided
        $visitor = null;
        
        if (!empty($data['email'])) {
            $email = strtolower(trim($data['email']));
            $this->db->where('LOWER(email)', $email);
            $query = $this->db->get('visitors');
            if ($query->num_rows() > 0) {
                $visitor = $query->row();
            }
        }
        
        // If not found by email, try phone if provided
        if (!$visitor && !empty($data['phone'])) {
            $phone = trim($data['phone']);
            $this->db->where('phone', $phone);
            $query = $this->db->get('visitors');
            if ($query->num_rows() > 0) {
                $visitor = $query->row();
            }
        }
        
        if ($visitor) {
            // Get total visits count
            $this->db->where('visitor_id', $visitor->visitor_id);
            $visits_query = $this->db->get('visits');
            $total_visits = $visits_query->num_rows();
            
            // Return visitor data
            echo json_encode([
                'status' => 'success',
                'visitor' => [
                    'visitor_id' => $visitor->visitor_id,
                    'first_name' => $visitor->first_name,
                    'last_name' => $visitor->last_name,
                    'email' => $visitor->email,
                    'phone' => $visitor->phone,
                    'company' => $visitor->company,
                    'photo' => $visitor->photo,
                    'visitor_type' => $visitor->visitor_type,
                    'company_visited' => $visitor->company_visited,
                    'total_visits' => $total_visits
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Visitor not found'
            ]);
        }
    }
    
    // Add these methods to your existing Kiosk.php controller

    /**
     * Get all active purposes for kiosk display
     */
    // public function get_purposes() {
    //     $purposes = $this->db->select('purpose_id, purpose_code, purpose_name, icon_class, color_class')
    //                         ->from('purposes')
    //                         ->where('is_active', 1)
    //                         ->order_by('display_order', 'ASC')
    //                         ->get()
    //                         ->result_array();
        
    //     echo json_encode(['status' => 'success', 'purposes' => $purposes]);
    // }
    
    /**
     * Get all active purposes for kiosk display - FILTERED BY COMPANY
     */
    // public function get_purposes() {
    //     // Get company_visited parameter from query string
    //     $company_visited = $this->input->get('company_visited');
        
    //     // Validate company_visited parameter
    //     if (empty($company_visited)) {
    //         echo json_encode([
    //             'status' => 'error', 
    //             'message' => 'Company parameter is required'
    //         ]);
    //         return;
    //     }
        
    //     // Query purposes that match the company OR are set to "Both"
    //     // IMPORTANT: The database column is 'company_owned_by', not 'company_visited'
    //     $this->db->select('purpose_id, purpose_code, purpose_name, icon_class, color_class')
    //             ->from('purposes')
    //             ->where('is_active', 1)
    //             ->group_start()
    //                 ->where('company_owned_by', $company_visited)
    //                 ->or_where('company_owned_by', 'Both')
    //             ->group_end()
    //             ->order_by('display_order', 'ASC');
        
    //     $purposes = $this->db->get()->result_array();
        
    //     echo json_encode(['status' => 'success', 'purposes' => $purposes]);
    // }
    
    /**
     * Get all active purposes for kiosk display - FILTERED BY COMPANY with TRANSLATIONS
     */
    public function get_purposes() {
        // Get company_visited parameter from query string
        $company_visited = $this->input->get('company_visited');
        
        // Validate company_visited parameter
        if (empty($company_visited)) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Company parameter is required'
            ]);
            return;
        }
        
        // Query purposes that match the company OR are set to "Both"
        // SELECT all translation columns
        $this->db->select('purpose_id, purpose_code, purpose_name, icon_class, color_class, 
                        name_en, name_zh_tw, name_zh_cn, name_fil, name_ja')
                ->from('purposes')
                ->where('is_active', 1)
                ->group_start()
                    ->where('company_owned_by', $company_visited)
                    ->or_where('company_owned_by', 'Both')
                ->group_end()
                ->order_by('display_order', 'ASC');
        
        $purposes = $this->db->get()->result_array();
        
        echo json_encode(['status' => 'success', 'purposes' => $purposes]);
    }

    /**
     * Get all purposes for admin (including inactive)
     */
    public function get_all_purposes() {
        $purposes = $this->db->select('*')
                            ->from('purposes')
                            ->order_by('display_order', 'ASC')
                            ->get()
                            ->result_array();
        
        echo json_encode(['status' => 'success', 'purposes' => $purposes]);
    }

    /**
     * Add a new purpose
     */
    public function add_purpose() {
        if ($this->input->method() !== 'post') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            return;
        }
        
        $purpose_code = strtolower(trim($this->input->post('purpose_code')));
        $purpose_name = trim($this->input->post('purpose_name'));
        $icon_class = trim($this->input->post('icon_class'));
        $color_class = trim($this->input->post('color_class'));
        $is_active = $this->input->post('is_active') ? 1 : 0;
        
        // Validate required fields
        if (empty($purpose_code) || empty($purpose_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Purpose code and name are required']);
            return;
        }
        
        // Check if purpose code already exists
        $existing = $this->db->get_where('purposes', ['purpose_code' => $purpose_code])->row();
        if ($existing) {
            echo json_encode(['status' => 'error', 'message' => 'Purpose code already exists']);
            return;
        }
        
        // Get the next display order
        $max_order = $this->db->select_max('display_order')->get('purposes')->row()->display_order;
        $display_order = $max_order ? $max_order + 1 : 1;
        
        $data = [
            'purpose_code' => $purpose_code,
            'purpose_name' => $purpose_name,
            'icon_class' => $icon_class ?: 'bi-circle',
            'color_class' => $color_class ?: 'text-primary',
            'display_order' => $display_order,
            'is_active' => $is_active,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->db->insert('purposes', $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Purpose added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add purpose']);
        }
    }

    /**
     * Toggle purpose active status
     */
    public function toggle_purpose_status() {
        if ($this->input->method() !== 'post') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            return;
        }
        
        $purpose_id = $this->input->post('purpose_id');
        $new_status = $this->input->post('new_status') ? 1 : 0;
        
        if (!$purpose_id) {
            echo json_encode(['status' => 'error', 'message' => 'Purpose ID required']);
            return;
        }
        
        if ($this->db->where('purpose_id', $purpose_id)->update('purposes', ['is_active' => $new_status])) {
            echo json_encode(['status' => 'success', 'new_status' => $new_status]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update purpose status']);
        }
        
    }

    /**
     * Update purpose display order
     */
    public function update_purpose_order() {
        if ($this->input->method() !== 'post') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            return;
        }
        
        $purpose_id = $this->input->post('purpose_id');
        $direction = $this->input->post('direction'); // 'up' or 'down'
        
        if (!$purpose_id || !$direction) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
            return;
        }
        
        // Get current purpose
        $current = $this->db->get_where('purposes', ['purpose_id' => $purpose_id])->row();
        if (!$current) {
            echo json_encode(['status' => 'error', 'message' => 'Purpose not found']);
            return;
        }
        
        // Get adjacent purpose
        if ($direction === 'up') {
            $this->db->where('display_order <', $current->display_order);
            $this->db->order_by('display_order', 'DESC');
        } else {
            $this->db->where('display_order >', $current->display_order);
            $this->db->order_by('display_order', 'ASC');
        }
        $adjacent = $this->db->limit(1)->get('purposes')->row();
        
        if (!$adjacent) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot move further']);
            return;
        }
        
        // Swap display orders
        $this->db->trans_start();
        $this->db->where('purpose_id', $current->purpose_id)->update('purposes', ['display_order' => $adjacent->display_order]);
        $this->db->where('purpose_id', $adjacent->purpose_id)->update('purposes', ['display_order' => $current->display_order]);
        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update order']);
        }
    }

    
    /**
     * Get visitor information by badge number (for QR code scanning)
     */
    // public function get_visitor_by_badge() {
    //     header('Content-Type: application/json');
        
    //     if ($this->input->method() !== 'post') {
    //         echo json_encode([
    //             'status' => 'error',
    //             'message' => 'Invalid request method'
    //         ]);
    //         return;
    //     }
        
    //     $json = file_get_contents('php://input');
    //     $data = json_decode($json, true);
        
    //     if (!$data || !isset($data['badge_number'])) {
    //         echo json_encode([
    //             'status' => 'error',
    //             'message' => 'Badge number is required'
    //         ]);
    //         return;
    //     }
        
    //     $badge_number = trim($data['badge_number']);
        
    //     // Validate badge number format
    //     if (!preg_match('/^V-\d{4}-\d{4}$/', $badge_number)) {
    //         echo json_encode([
    //             'status' => 'error',
    //             'message' => 'Invalid badge number format'
    //         ]);
    //         return;
    //     }
        
    //     // Query database
    //     $this->db->select('
    //         vis.visitor_id,
    //         vis.first_name,
    //         vis.last_name,
    //         vis.email,
    //         vis.phone,
    //         vis.company,
    //         vis.photo,
    //         vis.visitor_type,
    //         vis.company_visited,
    //         v.visit_id,
    //         v.badge_number,
    //         v.check_in_time,
    //         v.valid_until,
    //         (SELECT COUNT(*) FROM visits WHERE visitor_id = vis.visitor_id) as total_visits
    //     ')
    //     ->from('visits v')
    //     ->join('visitors vis', 'v.visitor_id = vis.visitor_id', 'inner')
    //     ->where('v.badge_number', $badge_number)
    //     ->order_by('v.visit_id', 'DESC')
    //     ->limit(1);
        
    //     $query = $this->db->get();
        
    //     if ($query->num_rows() > 0) {
    //         $visitor = $query->row();
            
    //         echo json_encode([
    //             'status' => 'success',
    //             'visitor' => [
    //                 'visitor_id' => $visitor->visitor_id,
    //                 'first_name' => $visitor->first_name,
    //                 'last_name' => $visitor->last_name,
    //                 'email' => $visitor->email,
    //                 'phone' => $visitor->phone,
    //                 'company' => $visitor->company,
    //                 'photo' => $visitor->photo,
    //                 'visitor_type' => $visitor->visitor_type,
    //                 'company_visited' => $visitor->company_visited,
    //                 'badge_number' => $visitor->badge_number,
    //                 'total_visits' => (int)$visitor->total_visits,
    //                 'last_visit' => $visitor->check_in_time
    //             ]
    //         ]);
    //     } else {
    //         echo json_encode([
    //             'status' => 'error',
    //             'message' => 'Badge number not found in our records'
    //         ]);
    //     }
    // }
    
    // // In your Kiosk controller
    // public function get_all_employees() {
    //     $company_visited = $this->input->get('company_visited');
        
    //     if (!$company_visited) {
    //         echo json_encode(['status' => 'error', 'message' => 'Company not specified']);
    //         return;
    //     }
        
    //     // Query all active employees with department info
    //     $this->db->select('e.employee_id, e.name, e.email, e.department_code, d.name as department_name')
    //             ->from('employees e')
    //             ->join('departments d', 'e.department_code = d.department_code', 'left')
    //             ->where('e.is_active', 1)
    //             ->group_start()
    //                 ->where('e.company_owned_by', $company_visited)
    //                 ->or_where('e.company_owned_by', 'Both')
    //             ->group_end()
    //             ->order_by('e.name', 'ASC');
        
    //     $employees = $this->db->get()->result_array();
        
    //     echo json_encode([
    //         'status' => 'success',
    //         'employees' => $employees
    //     ]);
    // }


    // // Get all employees with active visit count
    // public function get_all_employees() {
    //     $company_visited = $this->input->get('company_visited') ?? 'Toms World';
        
    //     // Query to get employees with active visit count
    //     $sql = "SELECT 
    //                 e.employee_id,
    //                 e.name,
    //                 e.email,
    //                 e.position,
    //                 e.phone_number,
    //                 e.profile_pic,
    //                 e.department_code,
    //                 d.name as department_name,
    //                 COALESCE(active_counts.active_visits, 0) as active_visits
    //             FROM employees e
    //             LEFT JOIN departments d ON e.department_code = d.department_code
    //             LEFT JOIN (
    //                 SELECT 
    //                     host_employee_id,
    //                     COUNT(*) as active_visits
    //                 FROM visits
    //                 WHERE check_out_time IS NULL
    //                 AND company_visited = ?
    //                 GROUP BY host_employee_id
    //             ) active_counts ON e.employee_id = active_counts.host_employee_id
    //             WHERE e.is_active = 1
    //             AND (e.company_filter = ? OR e.company_filter = 'Both' OR e.company_owned_by = 'Both')
    //             ORDER BY e.name ASC";
        
    //     $query = $this->db->query($sql, [$company_visited, $company_visited]);
    //     $employees = $query->result_array();
        
    //     echo json_encode([
    //         'status' => 'success',
    //         'employees' => $employees
    //     ]);
    // }

    // Get all employees with active visit count
    public function get_all_employees() {
        header('Content-Type: application/json');
        
        $company_visited = $this->input->get('company_visited') ?? 'Toms World';
        
        $sql = "SELECT 
                    e.employee_id,
                    e.name,
                    e.email,
                    e.position,
                    e.phone_number,
                    e.profile_pic,
                    e.department_code,
                    d.name as department_name,
                    COALESCE(active_counts.active_visits, 0) as active_visits
                FROM employees e
                LEFT JOIN departments d ON e.department_code = d.department_code
                LEFT JOIN (
                    SELECT 
                        host_employee_id,
                        COUNT(*) as active_visits
                    FROM visits
                    WHERE check_out_time IS NULL
                    AND valid_until > NOW()
                    GROUP BY host_employee_id
                ) active_counts ON e.employee_id = active_counts.host_employee_id
                WHERE e.is_active = 1
                AND (
                    e.company_owned_by = ? 
                    OR e.company_owned_by = 'Both' 
                    OR e.company_filter = ? 
                    OR e.company_filter = 'Both'
                )
                ORDER BY e.name ASC";
        
        $query = $this->db->query($sql, [$company_visited, $company_visited]);
        $employees = $query->result_array();
        
        echo json_encode([
            'status' => 'success',
            'employees' => $employees
        ]);
    }
}