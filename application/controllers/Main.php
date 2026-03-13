<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->database();
    }
    
    // Template helper method for TW (Toms World) - WITH header and footer
    private function render_template_tw($content_view, $data = array()) {
        $default_data = array(
            'company' => 'TOMS WORLD',
            'company_code' => 'tw'
        );
        $data = array_merge($default_data, $data);
        
        $this->load->view('partials/__header_tw', $data);
        $this->load->view($content_view, $data);
        $this->load->view('partials/__footer', $data);
        // $this->load->view('errors/html/error_restrict', $data);  // Temporary Access Restriction Page, Uncomment Above to Work
    }

    // Template helper method for PA (Pan Asia) - WITH header and footer
    private function render_template_pa($content_view, $data = array()) {
        $default_data = array(
            'company' => 'PAN-ASIA INTERNATIONAL',
            'company_code' => 'pa'
        );
        $data = array_merge($default_data, $data);
        
        $this->load->view('partials/__header_pa', $data);
        $this->load->view($content_view, $data);
        $this->load->view('partials/__footer', $data);        
        // $this->load->view('errors/html/error_restrict', $data);  // Temporary Access Restriction Page, Uncomment Above to Work
    }

    // Template helper method for Admin - NO header and footer
    private function render_template_admin($content_view, $data = array()) {
        $current_page = $this->router->fetch_method();
        $data['current_page'] = $current_page;
        $this->load->view($content_view, $data);
    }

    // Template helper method for Login - NO header and footer
    private function render_template_login($content_view, $data = array()) {
        $current_page = $this->router->fetch_method();
        $data['current_page'] = $current_page;
        $this->load->view($content_view, $data);
    }

    // Helper method to get company filter based on username
    private function getCompanyFilter($username) {
        $username = strtolower($username);
        if ($username === 'tw_admin') {
            return 'Toms World';
        } elseif ($username === 'pa_admin') {
            return 'Pan Asia';
        }
        return null;
    }
    
    // Helper method to set page info based on company filter
    private function setPageInfo($companyFilter) {
        $pageInfo = array();
        
        if ($companyFilter === 'Toms World') {
            $pageInfo['pageTitle'] = "Tom's World";
            $pageInfo['welcomeMessage'] = "Welcome back! Here's what's happening today at Tom's World.";
            $pageInfo['modalHeaderClass'] = 'tw-admin';
        } elseif ($companyFilter === 'Pan Asia') {
            $pageInfo['pageTitle'] = "Pan-Asia";
            $pageInfo['welcomeMessage'] = "Welcome back! Here's what's happening today at Pan-Asia.";
            $pageInfo['modalHeaderClass'] = 'pa-admin';
        } else {
            $pageInfo['pageTitle'] = "Tom's World & Pan-Asia";
            $pageInfo['welcomeMessage'] = "Welcome back! Here's what's happening today at Tom's World & Pan-Asia.";
            $pageInfo['modalHeaderClass'] = 'super-admin';
        }
        
        return $pageInfo;
    }

    // ==================== PUBLIC METHODS ====================
    
    // Welcome/Index page - Show login
    public function index() {
        // If already logged in, go to admin
        if ($this->session->userdata('logged_in')) {
            redirect('main/admin');
        }
        $this->load->view('auth/login');
        // $this->load->view('errors/html/error_restrict');  // Temporary Access Restriction Page, Uncomment Above to Work
    }

    // Admin page - Protected, requires login
    public function admin() {
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            // Not logged in, redirect to login page
            $this->session->set_flashdata('error', 'Please login to access admin panel');
            redirect('auth/login');
        }
        
        // Load the Admin model
        $this->load->model('Admin_model');
        
        // Get user info from session
        $logged_in_user = strtolower($this->session->userdata('username'));
        $user_role = $this->session->userdata('role');
        $companyFilter = $this->getCompanyFilter($logged_in_user);
        
        // Get page info
        $pageInfo = $this->setPageInfo($companyFilter);
        
        try {
            // Load initial dashboard data
            $dashboardStats = $this->Admin_model->getDashboardStats($companyFilter);
            $recentActivity = $this->Admin_model->getRecentActivity($companyFilter);
            $activeVisits = $this->Admin_model->getActiveVisits($companyFilter);
            
            // Prepare data for view
            $data = array(
                'page_title' => 'Admin Dashboard',
                'logged_in_user' => $logged_in_user,
                'user_role' => $user_role,
                'companyFilter' => $companyFilter,
                'pageTitle' => $pageInfo['pageTitle'],
                'welcomeMessage' => $pageInfo['welcomeMessage'],
                'modalHeaderClass' => $pageInfo['modalHeaderClass'],
                'dashboardStats' => $dashboardStats,
                'recentActivity' => $recentActivity,
                'activeVisits' => $activeVisits
            );
            
            $this->render_template_admin('main/admin', $data);
            
        } catch (Exception $e) {
            // Log the error
            log_message('error', 'Admin Dashboard Error: ' . $e->getMessage());
            
            // Show friendly error
            show_error('Unable to load admin dashboard. Please check database connection.', 500);
        }
    }

    // AJAX Handler for admin operations
    public function ajax_handler() {
        // Load the Admin model
        $this->load->model('Admin_model');
        
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
    
    // TW (Toms World) page - WITH header and footer (orange theme)
    public function tw() {
        $data = array(
            'page_title' => 'TOMS WORLD Portal',
            'theme' => 'orange'
        );
        $this->render_template_tw('main/tw', $data);
    }
    
    // PA (Pan Asia) page - WITH header and footer (green theme)
    public function pa() {
        $data = array(
            'page_title' => 'PAN-ASIA INTERNATIONAL Portal',
            'theme' => 'green'
        );
        $this->render_template_pa('main/pa', $data);
        
    }

    public function revisit() {
        $data = array(
            'page_title' => 'PAN-ASIA INTERNATIONAL Portal',
            'theme' => 'green'
        );
        $this->render_template_pa('main/revisit', $data);
    }

    // Login page - NO header/footer
    public function login() {
        $data = array(
            'page_title' => 'Login'
        );
        // $this->render_template_login('auth/login', $data);
        // $this->render_template_login('errors/html/error_restrict', $data);  // Temporary Access Restriction Page, Uncomment Above to Work
    }
}