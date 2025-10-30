<?php
// application/models/Visitor_model.php

class Visitor_model extends CI_Model {
    
    protected $table = 'visitors';
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get all visitors with company info
    public function get_all_visitors($limit = null, $offset = null) {
        $this->db->select('visitors.*, companies.company_name as company_full_name');
        $this->db->from($this->table);
        $this->db->join('companies', 'visitors.company_id = companies.id', 'left');
        $this->db->where('visitors.is_blacklisted', FALSE);
        $this->db->order_by('visitors.created_at', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result_array();
    }
    
    // Get visitor by ID
    public function get_visitor($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }
    
    // Search visitors
    public function search_visitors($query) {
        $this->db->select('visitors.*, companies.company_name as company_full_name');
        $this->db->from($this->table);
        $this->db->join('companies', 'visitors.company_id = companies.id', 'left');
        $this->db->group_start()
            ->like('visitors.first_name', $query)
            ->or_like('visitors.last_name', $query)
            ->or_like('visitors.email', $query)
            ->or_like('visitors.phone', $query)
            ->or_like('visitors.visitor_code', $query)
            ->or_like('companies.company_name', $query)
        ->group_end();
        $this->db->where('visitors.is_blacklisted', FALSE);
        
        return $this->db->get()->result_array();
    }
    
    // Create new visitor
    public function create_visitor($data) {
        // Generate visitor code
        $data['visitor_code'] = $this->generate_visitor_code();
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    
    // Update visitor
    public function update_visitor($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    // Check if visitor exists by email or phone
    public function visitor_exists($email = null, $phone = null) {
        if ($email) {
            $this->db->or_where('email', $email);
        }
        if ($phone) {
            $this->db->or_where('phone', $phone);
        }
        
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0 ? $query->row_array() : false;
    }
    
    // Generate unique visitor code
    private function generate_visitor_code() {
        $prefix = 'VIS';
        $year = date('Y');
        
        // Get last visitor code
        $this->db->select('visitor_code');
        $this->db->from($this->table);
        $this->db->like('visitor_code', $prefix . '-' . $year, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get()->row_array();
        
        if ($last && preg_match('/(\d+)$/', $last['visitor_code'], $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }
        
        return $prefix . '-' . $year . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
    
    // Get blacklisted visitors
    public function get_blacklisted() {
        $this->db->where('is_blacklisted', TRUE);
        return $this->db->get($this->table)->result_array();
    }
}

// application/models/Visit_model.php

class Visit_model extends CI_Model {
    
    protected $table = 'visits';
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get all visits with related data
    public function get_all_visits($filter = []) {
        $this->db->select('
            visits.*,
            CONCAT(visitors.first_name, " ", visitors.last_name) as visitor_name,
            visitors.email as visitor_email,
            visitors.phone as visitor_phone,
            visitors.photo_path as visitor_photo,
            COALESCE(companies.company_name, visitors.company_name) as company,
            CONCAT(employees.first_name, " ", employees.last_name) as host_name,
            employees.email as host_email,
            visit_purposes.purpose_name
        ');
        $this->db->from($this->table);
        $this->db->join('visitors', 'visits.visitor_id = visitors.id', 'left');
        $this->db->join('companies', 'visitors.company_id = companies.id', 'left');
        $this->db->join('employees', 'visits.host_id = employees.id', 'left');
        $this->db->join('visit_purposes', 'visits.purpose_id = visit_purposes.id', 'left');
        
        // Apply filters
        if (!empty($filter['date_from'])) {
            $this->db->where('DATE(visits.check_in_time) >=', $filter['date_from']);
        }
        if (!empty($filter['date_to'])) {
            $this->db->where('DATE(visits.check_in_time) <=', $filter['date_to']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('visits.status', $filter['status']);
        }
        if (!empty($filter['host_id'])) {
            $this->db->where('visits.host_id', $filter['host_id']);
        }
        if (!empty($filter['purpose_id'])) {
            $this->db->where('visits.purpose_id', $filter['purpose_id']);
        }
        
        $this->db->order_by('visits.check_in_time', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    // Get currently checked-in visitors
    public function get_active_visitors() {
        $this->db->select('
            visits.*,
            CONCAT(visitors.first_name, " ", visitors.last_name) as visitor_name,
            visitors.photo_path,
            COALESCE(companies.company_name, visitors.company_name) as company,
            CONCAT(employees.first_name, " ", employees.last_name) as host_name,
            visit_purposes.purpose_name,
            TIMESTAMPDIFF(MINUTE, visits.check_in_time, NOW()) as duration_minutes
        ');
        $this->db->from($this->table);
        $this->db->join('visitors', 'visits.visitor_id = visitors.id', 'left');
        $this->db->join('companies', 'visitors.company_id = companies.id', 'left');
        $this->db->join('employees', 'visits.host_id = employees.id', 'left');
        $this->db->join('visit_purposes', 'visits.purpose_id = visit_purposes.id', 'left');
        $this->db->where('visits.status', 'checked-in');
        $this->db->order_by('visits.check_in_time', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    // Check in visitor
    public function check_in($data) {
        // Generate visit code
        $data['visit_code'] = $this->generate_visit_code();
        $data['check_in_time'] = date('Y-m-d H:i:s');
        $data['status'] = 'checked-in';
        $data['check_in_by'] = $this->session->userdata('user_id');
        
        $this->db->trans_start();
        
        // Insert visit
        $this->db->insert($this->table, $data);
        $visit_id = $this->db->insert_id();
        
        // Log the check-in
        $this->log_visit_action($visit_id, 'check_in', 'Visitor checked in');
        
        // Send notification to host
        if (!empty($data['host_id'])) {
            $this->send_host_notification($visit_id, $data['host_id']);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() ? $visit_id : false;
    }
    
    // Check out visitor
    public function check_out($visit_id) {
        $this->db->trans_start();
        
        // Update visit
        $this->db->where('id', $visit_id);
        $this->db->update($this->table, [
            'check_out_time' => date('Y-m-d H:i:s'),
            'status' => 'checked-out',
            'check_out_by' => $this->session->userdata('user_id')
        ]);
        
        // Log the check-out
        $this->log_visit_action($visit_id, 'check_out', 'Visitor checked out');
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }
    
    // Auto checkout old visits
    public function auto_checkout() {
        // Get auto-checkout setting
        $this->db->select('setting_value');
        $this->db->from('settings');
        $this->db->where('setting_key', 'auto_checkout_hours');
        $setting = $this->db->get()->row_array();
        $hours = $setting ? intval($setting['setting_value']) : 8;
        
        // Find visits to auto-checkout
        $cutoff_time = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));
        
        $this->db->where('status', 'checked-in');
        $this->db->where('check_in_time <', $cutoff_time);
        $visits = $this->db->get($this->table)->result_array();
        
        foreach ($visits as $visit) {
            $this->db->where('id', $visit['id']);
            $this->db->update($this->table, [
                'check_out_time' => date('Y-m-d H:i:s'),
                'status' => 'checked-out'
            ]);
            
            $this->log_visit_action($visit['id'], 'check_out', 'Auto checkout after ' . $hours . ' hours');
        }
        
        return count($visits);
    }
    
    // Generate unique visit code
    private function generate_visit_code() {
        $prefix = 'V';
        $date = date('Ymd');
        
        // Get count of today's visits
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        $count = $this->db->count_all_results($this->table) + 1;
        
        return $prefix . '-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
    
    // Log visit action
    private function log_visit_action($visit_id, $action, $description) {
        $this->db->insert('visit_logs', [
            'visit_id' => $visit_id,
            'action' => $action,
            'description' => $description,
            'performed_by' => $this->session->userdata('user_id'),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent()
        ]);
    }
    
    // Send notification to host
    private function send_host_notification($visit_id, $host_id) {
        // Get visit details
        $this->db->select('
            visits.*,
            CONCAT(visitors.first_name, " ", visitors.last_name) as visitor_name,
            COALESCE(companies.company_name, visitors.company_name) as company
        ');
        $this->db->from($this->table);
        $this->db->join('visitors', 'visits.visitor_id = visitors.id', 'left');
        $this->db->join('companies', 'visitors.company_id = companies.id', 'left');
        $this->db->where('visits.id', $visit_id);
        $visit = $this->db->get()->row_array();
        
        // Get host details
        $this->db->where('id', $host_id);
        $host = $this->db->get('employees')->row_array();
        
        if ($visit && $host) {
            $this->db->insert('notifications', [
                'type' => 'visitor_arrived',
                'recipient_id' => $host_id,
                'recipient_email' => $host['email'],
                'subject' => 'Visitor Arrived: ' . $visit['visitor_name'],
                'message' => sprintf(
                    '%s from %s has arrived and is waiting for you at the reception. Badge Number: %s',
                    $visit['visitor_name'],
                    $visit['company'],
                    $visit['badge_number']
                ),
                'related_visit_id' => $visit_id
            ]);
        }
    }
    
    // Get visit statistics
    public function get_statistics($period = 'today') {
        $stats = [];
        
        switch ($period) {
            case 'today':
                $date_condition = 'DATE(check_in_time) = CURDATE()';
                break;
            case 'week':
                $date_condition = 'YEARWEEK(check_in_time, 1) = YEARWEEK(CURDATE(), 1)';
                break;
            case 'month':
                $date_condition = 'MONTH(check_in_time) = MONTH(CURDATE()) AND YEAR(check_in_time) = YEAR(CURDATE())';
                break;
            default:
                $date_condition = '1=1';
        }
        
        // Total visitors
        $this->db->where($date_condition);
        $stats['total_visitors'] = $this->db->count_all_results($this->table);
        
        // Currently in building
        $this->db->where('status', 'checked-in');
        $stats['currently_in'] = $this->db->count_all_results($this->table);
        
        // Average duration
        $this->db->select('AVG(TIMESTAMPDIFF(MINUTE, check_in_time, IFNULL(check_out_time, NOW()))) as avg_duration');
        $this->db->from($this->table);
        $this->db->where($date_condition);
        $result = $this->db->get()->row_array();
        $stats['avg_duration_minutes'] = round($result['avg_duration'] ?? 0);
        
        // Visit purpose breakdown
        $this->db->select('visit_purposes.purpose_name, COUNT(*) as count');
        $this->db->from($this->table);
        $this->db->join('visit_purposes', 'visits.purpose_id = visit_purposes.id', 'left');
        $this->db->where($date_condition);
        $this->db->group_by('visits.purpose_id');
        $stats['by_purpose'] = $this->db->get()->result_array();
        
        return $stats;
    }
}

// application/models/Employee_model.php

class Employee_model extends CI_Model {
    
    protected $table = 'employees';
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get all employees with department
    public function get_all_employees($filter = []) {
        $this->db->select('employees.*, departments.department_name');
        $this->db->from($this->table);
        $this->db->join('departments', 'employees.department_id = departments.id', 'left');
        
        if (!empty($filter['department_id'])) {
            $this->db->where('employees.department_id', $filter['department_id']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('employees.status', $filter['status']);
        }
        if (!empty($filter['can_host'])) {
            $this->db->where('employees.can_host', $filter['can_host']);
        }
        
        $this->db->order_by('employees.first_name, employees.last_name');
        
        return $this->db->get()->result_array();
    }
    
    // Get employees who can host
    public function get_hosts() {
        $this->db->select('id, CONCAT(first_name, " ", last_name) as name, email, department_id');
        $this->db->from($this->table);
        $this->db->where('can_host', TRUE);
        $this->db->where('status', 'active');
        $this->db->order_by('first_name, last_name');
        
        return $this->db->get()->result_array();
    }
    
    // Toggle host permission
    public function toggle_host_permission($id) {
        $employee = $this->db->get_where($this->table, ['id' => $id])->row_array();
        
        if ($employee) {
            $new_status = !$employee['can_host'];
            $this->db->where('id', $id);
            $this->db->update($this->table, ['can_host' => $new_status]);
            return $new_status;
        }
        
        return false;
    }
}

// application/models/Schedule_model.php

class Schedule_model extends CI_Model {
    
    protected $table = 'scheduled_visits';
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get scheduled visits
    public function get_scheduled_visits($date = null) {
        $this->db->select('
            scheduled_visits.*,
            CONCAT(employees.first_name, " ", employees.last_name) as host_name,
            employees.email as host_email,
            visit_purposes.purpose_name
        ');
        $this->db->from($this->table);
        $this->db->join('employees', 'scheduled_visits.host_id = employees.id', 'left');
        $this->db->join('visit_purposes', 'scheduled_visits.purpose_id = visit_purposes.id', 'left');
        
        if ($date) {
            $this->db->where('scheduled_visits.scheduled_date', $date);
        } else {
            $this->db->where('scheduled_visits.scheduled_date >=', date('Y-m-d'));
        }
        
        $this->db->where_in('scheduled_visits.approval_status', ['pending', 'approved']);
        $this->db->order_by('scheduled_visits.scheduled_date, scheduled_visits.scheduled_time');
        
        return $this->db->get()->result_array();
    }
    
    // Create scheduled visit
    public function create_schedule($data) {
        $data['schedule_code'] = $this->generate_schedule_code($data['purpose_id']);
        $data['created_by'] = $this->session->userdata('user_id');
        
        $this->db->insert($this->table, $data);
        $schedule_id = $this->db->insert_id();
        
        // Send notification to host
        $this->send_schedule_notification($schedule_id);
        
        return $schedule_id;
    }
    
    // Update scheduled visit
    public function update_schedule($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    // Approve/Reject scheduled visit
    public function update_approval($id, $status) {
        $data = [
            'approval_status' => $status,
            'approved_by' => $this->session->userdata('user_id'),
            'approval_date' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    // Convert scheduled visit to actual visit
    public function convert_to_visit($schedule_id, $visitor_id) {
        $schedule = $this->db->get_where($this->table, ['id' => $schedule_id])->row_array();
        
        if ($schedule && $schedule['approval_status'] === 'approved') {
            // Create visit record
            $this->load->model('Visit_model');
            $visit_data = [
                'visitor_id' => $visitor_id,
                'host_id' => $schedule['host_id'],
                'purpose_id' => $schedule['purpose_id'],
                'purpose_details' => $schedule['purpose_details'],
                'expected_duration' => $schedule['expected_duration']
            ];
            
            $visit_id = $this->Visit_model->check_in($visit_data);
            
            if ($visit_id) {
                // Update schedule with visit ID
                $this->db->where('id', $schedule_id);
                $this->db->update($this->table, ['visit_id' => $visit_id]);
                
                return $visit_id;
            }
        }
        
        return false;
    }
    
    // Generate schedule code based on purpose
    private function generate_schedule_code($purpose_id) {
        $prefixes = [
            1 => 'MEET',  // Meeting
            2 => 'INT',   // Interview
            3 => 'TRAIN', // Training
            7 => 'EVENT', // Event
            8 => 'TOUR'   // Tour
        ];
        
        $prefix = $prefixes[$purpose_id] ?? 'SCH';
        $year = date('Y');
        
        // Get last schedule code with this prefix
        $this->db->select('schedule_code');
        $this->db->from($this->table);
        $this->db->like('schedule_code', $prefix . '-' . $year, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get()->row_array();
        
        if ($last && preg_match('/(\d+)$/', $last['schedule_code'], $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }
        
        return $prefix . '-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
    
    // Send notification to host about scheduled visit
    private function send_schedule_notification($schedule_id) {
        $this->db->select('
            scheduled_visits.*,
            employees.email as host_email,
            CONCAT(employees.first_name, " ", employees.last_name) as host_name
        ');
        $this->db->from($this->table);
        $this->db->join('employees', 'scheduled_visits.host_id = employees.id', 'left');
        $this->db->where('scheduled_visits.id', $schedule_id);
        $schedule = $this->db->get()->row_array();
        
        if ($schedule) {
            $this->db->insert('notifications', [
                'type' => 'scheduled_reminder',
                'recipient_id' => $schedule['host_id'],
                'recipient_email' => $schedule['host_email'],
                'subject' => 'Scheduled Visit: ' . $schedule['visitor_name'],
                'message' => sprintf(
                    'You have a scheduled visit from %s (%s) on %s at %s. Purpose: %s',
                    $schedule['visitor_name'],
                    $schedule['visitor_company'],
                    date('F j, Y', strtotime($schedule['scheduled_date'])),
                    date('g:i A', strtotime($schedule['scheduled_time'])),
                    $schedule['purpose_details']
                )
            ]);
            
            // Mark notification as sent
            $this->db->where('id', $schedule_id);
            $this->db->update($this->table, ['notification_sent' => TRUE]);
        }
    }
    
    // Get calendar data
    public function get_calendar_data($month, $year) {
        $this->db->select('scheduled_date, COUNT(*) as count, GROUP_CONCAT(schedule_code) as codes');
        $this->db->from($this->table);
        $this->db->where('MONTH(scheduled_date)', $month);
        $this->db->where('YEAR(scheduled_date)', $year);
        $this->db->where_in('approval_status', ['pending', 'approved']);
        $this->db->group_by('scheduled_date');
        
        $result = $this->db->get()->result_array();
        
        $calendar_data = [];
        foreach ($result as $row) {
            $calendar_data[$row['scheduled_date']] = [
                'count' => $row['count'],
                'codes' => explode(',', $row['codes'])
            ];
        }
        
        return $calendar_data;
    }
}