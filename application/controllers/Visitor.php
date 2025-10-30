<?php
// application/controllers/Visitor.php

class Visitor extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form', 'date']);
        $this->load->model(['Visitor_model', 'Visit_model', 'Employee_model', 'Schedule_model']);
        
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }
    
    // Dashboard
    public function index() {
        $data['title'] = 'Visitor Management Dashboard';
        
        // Get statistics
        $data['stats'] = $this->Visit_model->get_statistics('today');
        $data['week_stats'] = $this->Visit_model->get_statistics('week');
        $data['month_stats'] = $this->Visit_model->get_statistics('month');
        
        // Get active visitors
        $data['active_visitors'] = $this->Visit_model->get_active_visitors();
        
        // Get today's scheduled visits
        $data['scheduled_today'] = $this->Schedule_model->get_scheduled_visits(date('Y-m-d'));
        
        // Get recent visits
        $data['recent_visits'] = $this->Visit_model->get_all_visits(['limit' => 10]);
        
        $this->load->view('templates/header', $data);
        $this->load->view('visitor/dashboard', $data);
        $this->load->view('templates/footer');
    }
    
    // Visitors list
    public function visitors() {
        $data['title'] = 'All Visitors';
        
        // Get filter parameters
        $filter = [
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to'),
            'status' => $this->input->get('status'),
            'purpose_id' => $this->input->get('purpose')
        ];
        
        $data['visits'] = $this->Visit_model->get_all_visits($filter);
        $data['filter'] = $filter;
        
        $this->load->view('templates/header', $data);
        $this->load->view('visitor/list', $data);
        $this->load->view('templates/footer');
    }
    
    // Check-in form
    public function checkin() {
        $data['title'] = 'Visitor Check-In';
        
        if ($this->input->post()) {
            // Validation rules
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
            $this->form_validation->set_rules('phone', 'Phone', 'required|trim');
            $this->form_validation->set_rules('host_id', 'Host', 'required|numeric');
            $this->form_validation->set_rules('purpose_id', 'Purpose', 'required|numeric');
            
            if ($this->form_validation->run() === TRUE) {
                // Check if visitor exists
                $visitor = $this->Visitor_model->visitor_exists(
                    $this->input->post('email'),
                    $this->input->post('phone')
                );
                
                if (!$visitor) {
                    // Create new visitor
                    $visitor_data = [
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'email' => $this->input->post('email'),
                        'phone' => $this->input->post('phone'),
                        'company_name' => $this->input->post('company'),
                        'id_type' => $this->input->post('id_type'),
                        'id_number' => $this->input->post('id_number')
                    ];
                    
                    // Handle photo upload
                    if (!empty($_FILES['photo']['name'])) {
                        $config['upload_path'] = './uploads/visitors/';
                        $config['allowed_types'] = 'jpg|jpeg|png';
                        $config['max_size'] = 2048;
                        $config['file_name'] = 'visitor_' . time();
                        
                        $this->load->library('upload', $config);
                        
                        if ($this->upload->do_upload('photo')) {
                            $upload_data = $this->upload->data();
                            $visitor_data['photo_path'] = 'uploads/visitors/' . $upload_data['file_name'];
                        }
                    }
                    
                    $visitor_id = $this->Visitor_model->create_visitor($visitor_data);
                } else {
                    $visitor_id = $visitor['id'];
                }
                
                // Create visit record
                $visit_data = [
                    'visitor_id' => $visitor_id,
                    'host_id' => $this->input->post('host_id'),
                    'purpose_id' => $this->input->post('purpose_id'),
                    'purpose_details' => $this->input->post('purpose_details'),
                    'badge_number' => $this->input->post('badge_number'),
                    'vehicle_number' => $this->input->post('vehicle_number'),
                    'items_carried' => $this->input->post('items_carried'),
                    'floor_access' => $this->input->post('floor_access')
                ];
                
                $visit_id = $this->Visit_model->check_in($visit_data);
                
                if ($visit_id) {
                    $this->session->set_flashdata('success', 'Visitor checked in successfully');
                    redirect('visitor/print_badge/' . $visit_id);
                } else {
                    $this->session->set_flashdata('error', 'Failed to check in visitor');
                }
            }
        }
        
        // Get hosts and purposes for dropdowns
        $data['hosts'] = $this->Employee_model->get_hosts();
        $data['purposes'] = $this->db->get('visit_purposes')->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('visitor/checkin', $data);
        $this->load->view('templates/footer');
    }
    
    // Check-out visitor
    public function checkout($visit_id) {
        if ($this->Visit_model->check_out($visit_id)) {
            $this->session->set_flashdata('success', 'Visitor checked out successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to check out visitor');
        }
        
        redirect('visitor');
    }
    
    // Scheduled visits
    public function scheduled() {
        $data['title'] = 'Pre-Scheduled Visits';
        
        $data['scheduled'] = $this->Schedule_model->get_scheduled_visits();
        
        // Get calendar data
        $month = $this->input->get('month') ?: date('n');
        $year = $this->input->get('year') ?: date('Y');
        $data['calendar_data'] = $this->Schedule_model->get_calendar_data($month, $year);
        $data['current_month'] = $month;
        $data['current_year'] = $year;
        
        $this->load->view('templates/header', $data);
        $this->load->view('visitor/scheduled', $data);
        $this->load->view('templates/footer');
    }
    
    // Schedule a visit
    public function schedule() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('visitor_name', 'Visitor Name', 'required|trim');
            $this->form_validation->set_rules('visitor_email', 'Email', 'trim|valid_email');
            $this->form_validation->set_rules('visitor_phone', 'Phone', 'required|trim');
            $this->form_validation->set_rules('host_id', 'Host', 'required|numeric');
            $this->form_validation->set_rules('scheduled_date', 'Date', 'required');
            $this->form_validation->set_rules('scheduled_time', 'Time', 'required');
            
            if ($this->form_validation->run() === TRUE) {
                $schedule_data = [
                    'visitor_name' => $this->input->post('visitor_name'),
                    'visitor_email' => $this->input->post('visitor_email'),
                    'visitor_phone' => $this->input->post('visitor_phone'),
                    'visitor_company' => $this->input->post('visitor_company'),
                    'host_id' => $this->input->post('host_id'),
                    'purpose_id' => $this->input->post('purpose_id'),
                    'purpose_details' => $this->input->post('purpose_details'),
                    'scheduled_date' => $this->input->post('scheduled_date'),
                    'scheduled_time' => $this->input->post('scheduled_time'),
                    'expected_duration' => $this->input->post('expected_duration'),
                    'special_requirements' => $this->input->post('special_requirements')
                ];
                
                $schedule_id = $this->Schedule_model->create_schedule($schedule_data);
                
                if ($schedule_id) {
                    $this->session->set_flashdata('success', 'Visit scheduled successfully');
                    redirect('visitor/scheduled');
                }
            }
        }
        
        $data['title'] = 'Schedule Visit';
        $data['hosts'] = $this->Employee_model->get_hosts();
        $data['purposes'] = $this->db->get('visit_purposes')->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('visitor/schedule', $data);
        $this->load->view('templates/footer');
    }
    
    // Approve scheduled visit
    public function approve_schedule($id) {
        if ($this->Schedule_model->update_approval($id, 'approved')) {
            $this->session->set_flashdata('success', 'Visit approved');
        } else {
            $this->session->set_flashdata('error', 'Failed to approve visit');
        }
        redirect('visitor/scheduled');
    }
    
    // Employees
    public function employees() {
        $data['title'] = 'Employee Directory';
        
        $filter = [
            'department_id' => $this->input->get('department'),
            'status' => $this->input->get('status'),
            'can_host' => $this->input->get('can_host')
        ];
        
        $data['employees'] = $this->Employee_model->get_all_employees($filter);
        $data['departments'] = $this->db->get('departments')->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('visitor/employees', $data);
        $this->load->view('templates/footer');
    }
    
    // Toggle employee host permission
    public function toggle_host($id) {
        $new_status = $this->Employee_model->toggle_host_permission($id);
        
        echo json_encode([
            'success' => $new_status !== false,
            'can_host' => $new_status
        ]);
    }
    
    // Reports
    public function reports() {
        $data['title'] = 'Reports';
        
        // Get report parameters
        $report_type = $this->input->post('report_type') ?: 'visitor_summary';
        $date_from = $this->input->post('date_from') ?: date('Y-m-01');
        $date_to = $this->input->post('date_to') ?: date('Y-m-d');
        
        $data['report_type'] = $report_type;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        
        // Generate report data based on type
        switch ($report_type) {
            case 'visitor_summary':
                $data['report_data'] = $this->generate_visitor_summary($date_from, $date_to);
                break;
            case 'host_statistics':
                $data['report_data'] = $this->generate_host_statistics($date_from, $date_to);
                break;
            case 'department_analysis':
                $data['report_data'] = $this->generate_department_analysis($date_from, $date_to);
                break;
            case 'security_log':
                $data['report_data'] = $this->generate_security_log($date_from, $date_to);
                break;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('visitor/reports', $data);
        $this->load->view('templates/footer');
    }
    
    // Generate visitor summary report
    private function generate_visitor_summary($date_from, $date_to) {
        $this->db->select('
            DATE(check_in_time) as visit_date,
            COUNT(*) as total_visitors,
            COUNT(DISTINCT visitor_id) as unique_visitors,
            AVG(TIMESTAMPDIFF(MINUTE, check_in_time, IFNULL(check_out_time, NOW()))) as avg_duration
        ');
        $this->db->from('visits');
        $this->db->where('DATE(check_in_time) >=', $date_from);
        $this->db->where('DATE(check_in_time) <=', $date_to);
        $this->db->group_by('DATE(check_in_time)');
        $this->db->order_by('visit_date', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    // Generate host statistics
    private function generate_host_statistics($date_from, $date_to) {
        $this->db->select('
            CONCAT(employees.first_name, " ", employees.last_name) as host_name,
            departments.department_name,
            COUNT(visits.id) as total_visitors,
            AVG(TIMESTAMPDIFF(MINUTE, visits.check_in_time, IFNULL(visits.check_out_time, NOW()))) as avg_duration
        ');
        $this->db->from('visits');
        $this->db->join('employees', 'visits.host_id = employees.id', 'left');
        $this->db->join('departments', 'employees.department_id = departments.id', 'left');
        $this->db->where('DATE(visits.check_in_time) >=', $date_from);
        $this->db->where('DATE(visits.check_in_time) <=', $date_to);
        $this->db->group_by('visits.host_id');
        $this->db->order_by('total_visitors', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    // Generate department analysis
    private function generate_department_analysis($date_from, $date_to) {
        $this->db->select('
            departments.department_name,
            COUNT(visits.id) as total_visits,
            COUNT(DISTINCT visits.visitor_id) as unique_visitors,
            COUNT(DISTINCT DATE(visits.check_in_time)) as days_with_visits
        ');
        $this->db->from('visits');
        $this->db->join('employees', 'visits.host_id = employees.id', 'left');
        $this->db->join('departments', 'employees.department_id = departments.id', 'left');
        $this->db->where('DATE(visits.check_in_time) >=', $date_from);
        $this->db->where('DATE(visits.check_in_time) <=', $date_to);
        $this->db->group_by('departments.id');
        $this->db->order_by('total_visits', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    // Generate security log
    private function generate_security_log($date_from, $date_to) {
        $this->db->select('
            visit_logs.*,
            visits.visit_code,
            CONCAT(visitors.first_name, " ", visitors.last_name) as visitor_name,
            users.full_name as performed_by_name
        ');
        $this->db->from('visit_logs');
        $this->db->join('visits', 'visit_logs.visit_id = visits.id', 'left');
        $this->db->join('visitors', 'visits.visitor_id = visitors.id', 'left');
        $this->db->join('users', 'visit_logs.performed_by = users.id', 'left');
        $this->db->where('DATE(visit_logs.created_at) >=', $date_from);
        $this->db->where('DATE(visit_logs.created_at) <=', $date_to);
        $this->db->order_by('visit_logs.created_at', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    // Export data
    public function export($format = 'csv') {
        $filter = [
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to')
        ];
        
        $data = $this->Visit_model->get_all_visits($filter);
        
        switch ($format) {
            case 'csv':
                $this->export_csv($data);
                break;
            case 'excel':
                $this->export_excel($data);
                break;
            case 'pdf':
                $this->export_pdf($data);
                break;
        }
    }
    
    // Export to CSV
    private function export_csv($data) {
        $this->load->helper('csv');
        
        $filename = 'visitors_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header row
        fputcsv($output, ['Visit Code', 'Visitor Name', 'Company', 'Host', 'Purpose', 'Check In', 'Check Out', 'Status']);
        
        // Data rows
        foreach ($data as $row) {
            fputcsv($output, [
                $row['visit_code'],
                $row['visitor_name'],
                $row['company'],
                $row['host_name'],
                $row['purpose_name'],
                $row['check_in_time'],
                $row['check_out_time'],
                $row['status']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    // Export to Excel (requires PHPSpreadsheet)
    private function export_excel($data) {
        // Implementation would require PHPSpreadsheet library
        $this->session->set_flashdata('info', 'Excel export requires PHPSpreadsheet library');
        redirect('visitor/reports');
    }
    
    // Export to PDF (requires TCPDF or similar)
    private function export_pdf($data) {
        // Implementation would require TCPDF library
        $this->session->set_flashdata('info', 'PDF export requires TCPDF library');
        redirect('visitor/reports');
    }
    
    // Print visitor badge
    public function print_badge($visit_id) {
        $this->db->select('
            visits.*,
            CONCAT(visitors.first_name, " ", visitors.last_name) as visitor_name,
            COALESCE(companies.company_name, visitors.company_name) as company,
            visitors.photo_path,
            CONCAT(employees.first_name, " ", employees.last_name) as host_name,
            departments.department_name
        ');
        $this->db->from('visits');
        $this->db->join('visitors', 'visits.visitor_id = visitors.id', 'left');
        $this->db->join('companies', 'visitors.company_id = companies.id', 'left');
        $this->db->join('employees', 'visits.host_id = employees.id', 'left');
        $this->db->join('departments', 'employees.department_id = departments.id', 'left');
        $this->db->where('visits.id', $visit_id);
        
        $data['visit'] = $this->db->get()->row_array();
        
        $this->load->view('visitor/badge', $data);
    }
    
    // AJAX: Search visitors
    public function search() {
        $query = $this->input->get('q');
        $visitors = $this->Visitor_model->search_visitors($query);
        
        echo json_encode($visitors);
    }
    
    // AJAX: Get visitor details
    public function get_visitor_details($id) {
        $visitor = $this->Visitor_model->get_visitor($id);
        echo json_encode($visitor);
    }
    
    // Run auto-checkout (should be called by cron job)
    public function auto_checkout() {
        $count = $this->Visit_model->auto_checkout();
        echo "Auto checked-out {$count} visitors";
    }

    public function insertVisitor () {
        var_dump(" Helloe cortes daddy");
        die();
    }
}