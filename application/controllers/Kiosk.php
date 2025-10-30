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
    public function get_departments() {
        $departments = $this->db->select('department_code, name')
                               ->from('departments')
                               ->order_by('name', 'ASC')
                               ->get()
                               ->result_array();
        
        echo json_encode(['status' => 'success', 'departments' => $departments]);
    }
    
    // Get employees by department
    public function get_employees($department_code = null) {
        if (!$department_code) {
            echo json_encode(['status' => 'error', 'message' => 'Department code required']);
            return;
        }
        
        $employees = $this->db->select('employee_id, name, email')
                             ->from('employees')
                             ->where('department_code', $department_code)
                             ->where('is_active', 1)
                             ->order_by('name', 'ASC')
                             ->get()
                             ->result_array();
        
        echo json_encode(['status' => 'success', 'employees' => $employees]);
    }
    
    // Complete check-in and insert visitor data
    public function complete_checkin() {
        // Get JSON data from request
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!$data) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            return;
        }
        
        $this->db->trans_start();
        
        try {
            // Check if visitor exists
            $existing_visitor = $this->db->select('visitor_id')
                                        ->from('visitors')
                                        ->where('email', $data['email'])
                                        ->get()
                                        ->row();
            
            if ($existing_visitor) {
                // Update existing visitor
                $visitor_id = $existing_visitor->visitor_id;
                
                $visitor_update = [
                    'first_name' => $data['firstName'],
                    'last_name' => $data['lastName'],
                    'phone' => $data['phone'],
                    'company' => $data['company'],
                    'visitor_type' => $data['type'] ?? 'new',
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                // Update photo if provided
                if (isset($data['photo']) && !empty($data['photo'])) {
                    $visitor_update['photo'] = $data['photo'];
                }
                
                $this->db->where('visitor_id', $visitor_id)
                        ->update('visitors', $visitor_update);
            } else {
                // Insert new visitor
                $visitor_data = [
                    'first_name' => $data['firstName'],
                    'last_name' => $data['lastName'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'company' => $data['company'],
                    'photo' => $data['photo'] ?? null,
                    'visitor_type' => $data['type'] ?? 'new',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $this->db->insert('visitors', $visitor_data);
                $visitor_id = $this->db->insert_id();
            }
            
            // Generate badge number using stored procedure
            $this->db->query("CALL generate_badge_number(@badge_number)");
            $badge_result = $this->db->query("SELECT @badge_number as badge_number")->row();
            $badge_number = $badge_result->badge_number;
            
            // Calculate valid_until (8 hours from check-in)
            $check_in_time = date('Y-m-d H:i:s');
            $valid_until = date('Y-m-d H:i:s', strtotime('+8 hours'));
            
            // Insert visit record
            $visit_data = [
                'visitor_id' => $visitor_id,
                'host_employee_id' => $data['host']['id'],
                'badge_number' => $badge_number,
                'purpose' => $data['purpose'],
                'additional_notes' => $data['notes'] ?? null,
                'check_in_time' => $check_in_time,
                'valid_until' => $valid_until,
                'terms_accepted' => 1,
                'photo_consent' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('visits', $visit_data);
            $visit_id = $this->db->insert_id();
            
            // Check if this is a pre-scheduled visit and update status
            if (isset($data['booking_code'])) {
                $this->db->where('booking_code', $data['booking_code'])
                        ->update('pre_scheduled_visits', [
                            'status' => 'checked_in',
                            'visit_id' => $visit_id
                        ]);
            }
            
            // Send notification email to host (optional)
            $this->send_host_notification($data, $badge_number);
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            // Return success response with badge details
            echo json_encode([
                'status' => 'success',
                'message' => 'Check-in completed successfully',
                'data' => [
                    'badge_number' => $badge_number,
                    'visit_id' => $visit_id,
                    'valid_until' => $valid_until,
                    'visitor_name' => $data['firstName'] . ' ' . $data['lastName'],
                    'company' => $data['company'],
                    'host_name' => $data['host']['name']
                ]
            ]);
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Check-in failed: ' . $e->getMessage()
            ]);
        }
    }
    
    // Search for returning visitors by QR code data
    public function search_visitor() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!isset($data['email'])) {
            echo json_encode(['status' => 'error', 'message' => 'Email required']);
            return;
        }
        
        $visitor = $this->db->select('v.*, 
                                     (SELECT COUNT(*) FROM visits WHERE visitor_id = v.visitor_id) as total_visits')
                           ->from('visitors v')
                           ->where('email', $data['email'])
                           ->get()
                           ->row_array();
        
        if ($visitor) {
            echo json_encode(['status' => 'success', 'visitor' => $visitor]);
        } else {
            echo json_encode(['status' => 'not_found', 'message' => 'Visitor not found']);
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
}