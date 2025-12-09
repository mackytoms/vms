<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Admin_model');
        $this->load->helper('url'); // ADD THIS
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Get user info from session
        $this->data = []; // INITIALIZE THIS
        $this->data['logged_in_user'] = strtolower($this->session->userdata('username'));
        $this->data['user_role'] = $this->session->userdata('role');
        $this->data['companyFilter'] = $this->getCompanyFilter($this->data['logged_in_user']);
        
        // Set page title and welcome message
        $this->setPageInfo();
    }
    
    private function getCompanyFilter($username) {
        $username = strtolower($username);
        if ($username === 'tw_admin') {
            return 'Toms World';
        } elseif ($username === 'pa_admin') {
            return 'Pan Asia';
        }
        return null;
    }
    
    private function setPageInfo() {
        $filter = $this->data['companyFilter'];
        
        if ($filter === 'Toms World') {
            $this->data['pageTitle'] = "Tom's World";
            $this->data['welcomeMessage'] = "Welcome back! Here's what's happening today at Tom's World.";
            $this->data['modalHeaderClass'] = 'tw-admin';
        } elseif ($filter === 'Pan Asia') {
            $this->data['pageTitle'] = "Pan-Asia";
            $this->data['welcomeMessage'] = "Welcome back! Here's what's happening today at Pan-Asia.";
            $this->data['modalHeaderClass'] = 'pa-admin';
        } else {
            $this->data['pageTitle'] = "Tom's World & Pan-Asia";
            $this->data['welcomeMessage'] = "Welcome back! Here's what's happening today at Tom's World & Pan-Asia.";
            $this->data['modalHeaderClass'] = 'super-admin';
        }
    }

    public function index() {
        // Load initial dashboard data - ADD ERROR HANDLING
        try {
            $this->data['dashboardStats'] = $this->Admin_model->getDashboardStats($this->data['companyFilter']);
            $this->data['recentActivity'] = $this->Admin_model->getRecentActivity($this->data['companyFilter']);
            $this->data['activeVisits'] = $this->Admin_model->getActiveVisits($this->data['companyFilter']);
            
            $this->load->view('main/admin', $this->data);
        } catch (Exception $e) {
            // Log the error
            log_message('error', 'Admin Dashboard Error: ' . $e->getMessage());
            
            // Show friendly error
            show_error('Unable to load admin dashboard. Please check database connection.', 500);
        }
    }
    
    // AJAX Handlers
    public function ajax_handler() {
        $action = $this->input->get('action');
        $companyFilter = $this->input->get('company_filter');
        
        if ($companyFilter === 'null' || $companyFilter === '') {
            $companyFilter = null;
        }
        
        header('Content-Type: application/json');
        
        try {
            switch($action) {
                case 'dashboard_stats':
                    echo json_encode($this->Admin_model->getDashboardStats($companyFilter));
                    break;
                    
                case 'recent_activity':
                    echo json_encode($this->Admin_model->getRecentActivity($companyFilter));
                    break;
                    
                case 'active_visits':
                    echo json_encode($this->Admin_model->getActiveVisits($companyFilter));
                    break;
                    
                case 'all_visitors':
                    echo json_encode($this->Admin_model->getAllVisitors($companyFilter));
                    break;
                    
                case 'get_visitor':
                    $visitor_id = $this->input->get('visitor_id');
                    $visitor = $this->Admin_model->getVisitorById($visitor_id);
                    echo json_encode($visitor ? $visitor : ['error' => 'Visitor not found']);
                    break;
                    
                case 'get_visit':
                    $visit_id = $this->input->get('visit_id');
                    $visit = $this->Admin_model->getVisitById($visit_id);
                    echo json_encode($visit ? $visit : ['error' => 'Visit not found']);
                    break;
                    
                case 'employees':
                    echo json_encode($this->Admin_model->getEmployees());
                    break;
                    
                case 'departments':
                    echo json_encode($this->Admin_model->getDepartments());
                    break;
                    
                case 'dashboard_stats_by_company':
                    echo json_encode($this->Admin_model->getDashboardStatsByCompany($companyFilter));
                    break;
                    
                case 'visitor_history':
                    $visitor_id = $this->input->get('visitor_id');
                    echo json_encode($this->Admin_model->getVisitorHistory($visitor_id));
                    break;
                    
                case 'employee_history':
                    $employee_id = $this->input->get('employee_id');
                    echo json_encode($this->Admin_model->getEmployeeVisitHistory($employee_id));
                    break;
                    
                case 'department_employees':
                    $department_code = $this->input->get('department_code');
                    echo json_encode($this->Admin_model->getEmployeesByDepartment($department_code));
                    break;
                    
                case 'checkout':
                    $visit_id = $this->input->post('visit_id');
                    if ($visit_id) {
                        $result = $this->Admin_model->checkoutVisit($visit_id);
                        echo json_encode($result);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Visit ID required']);
                    }
                    break;
                    
                case 'add_employee':
                    $data = [
                        'name' => $this->input->post('name'),
                        'email' => $this->input->post('email'),
                        'department_code' => $this->input->post('department_code'),
                        'is_active' => $this->input->post('is_active') ? 1 : 0
                    ];
                    $result = $this->Admin_model->addEmployee($data);
                    echo json_encode($result);
                    break;
                    
                case 'toggle_employee_status':
                    $employee_id = $this->input->post('employee_id');
                    $new_status = (int)$this->input->post('new_status');
                    $result = $this->Admin_model->toggleEmployeeStatus($employee_id, $new_status);
                    echo json_encode($result);
                    break;
                    
                case 'add_department':
                    $data = [
                        'department_code' => $this->input->post('department_code'),
                        'name' => $this->input->post('name'),
                        'description' => $this->input->post('description') ?? ''
                    ];
                    $result = $this->Admin_model->addDepartment($data);
                    echo json_encode($result);
                    break;
                    
                case 'get_all_purposes':
                    echo json_encode($this->Admin_model->getAllPurposes());
                    break;
                    
                case 'add_purpose':
                    $data = [
                        'purpose_code' => strtolower(trim($this->input->post('purpose_code'))),
                        'purpose_name' => trim($this->input->post('purpose_name')),
                        'icon_class' => trim($this->input->post('icon_class') ?? 'bi-circle'),
                        'color_class' => trim($this->input->post('color_class') ?? 'text-primary'),
                        'is_active' => $this->input->post('is_active') ? 1 : 0
                    ];
                    $result = $this->Admin_model->addPurpose($data);
                    echo json_encode($result);
                    break;
                    
                case 'toggle_purpose_status':
                    $purpose_id = (int)$this->input->post('purpose_id');
                    $new_status = (int)$this->input->post('new_status');
                    $result = $this->Admin_model->togglePurposeStatus($purpose_id, $new_status);
                    echo json_encode($result);
                    break;
                    
                case 'update_purpose_order':
                    $purpose_id = (int)$this->input->post('purpose_id');
                    $direction = $this->input->post('direction');
                    $result = $this->Admin_model->updatePurposeOrder($purpose_id, $direction);
                    echo json_encode($result);
                    break;
                    
                case 'check_emergency_alerts':
                    echo json_encode($this->Admin_model->checkEmergencyAlerts($companyFilter));
                    break;
                    
                case 'get_last_alert_id':
                    echo json_encode($this->Admin_model->getLastAlertId($companyFilter));
                    break;
                    
                case 'acknowledge_emergency_alert':
                    $alert_id = (int)$this->input->post('alert_id');
                    $result = $this->Admin_model->acknowledgeEmergencyAlert($alert_id);
                    echo json_encode($result);
                    break;
                    
                default:
                    echo json_encode(['error' => 'Invalid action']);
            }
        } catch (Exception $e) {
            log_message('error', 'AJAX Error: ' . $e->getMessage());
            echo json_encode(['error' => 'Server error occurred', 'message' => $e->getMessage()]);
        }
    }
}