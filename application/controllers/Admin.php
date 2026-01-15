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

                case 'employees':
                    echo json_encode($this->Admin_model->getEmployees($companyFilter));
                    break;

                case 'get_employee':
                    $employee_id = $this->input->get('employee_id');
                    echo json_encode($this->Admin_model->getEmployeeById($employee_id));
                    break;
                    
                // case 'update_employee':
                //     $data = [
                //         'employee_id' => trim($this->input->post('employee_id')),
                //         'name' => trim($this->input->post('name')),
                //         'email' => trim($this->input->post('email')),
                //         'department_code' => trim($this->input->post('department_code')),
                //         'company_owned_by' => trim($this->input->post('company_owned_by') ?? 'Both'),
                //         'is_active' => $this->input->post('is_active') ? 1 : 0
                //     ];
                    
                //     // Check permissions
                //     $canEdit = $this->Admin_model->canEditEmployee($data['employee_id'], $companyFilter);
                //     if (!$canEdit['can_edit']) {
                //         echo json_encode(['status' => 'error', 'message' => 'You do not have permission to edit this employee']);
                //         break;
                //     }
                    
                //     $result = $this->Admin_model->updateEmployee($data);
                //     echo json_encode($result);
                //     break;

                case 'update_employee':
                    $data = [
                        'employee_id' => trim($this->input->post('employee_id')),
                        'name' => trim($this->input->post('name')),
                        'email' => trim($this->input->post('email')),
                        'phone_number' => trim($this->input->post('phone_number')) ?: null,  // ADDED
                        'position' => trim($this->input->post('position')) ?: null,          // ADDED
                        'department_code' => trim($this->input->post('department_code')),
                        'company_owned_by' => trim($this->input->post('company_owned_by') ?? 'Both'),
                        'is_active' => $this->input->post('is_active') ? 1 : 0
                    ];
                    
                    // Check permissions
                    $canEdit = $this->Admin_model->canEditEmployee($data['employee_id'], $companyFilter);
                    if (!$canEdit['can_edit']) {
                        echo json_encode(['status' => 'error', 'message' => 'You do not have permission to edit this employee']);
                        break;
                    }
                    
                    $result = $this->Admin_model->updateEmployee($data);
                    echo json_encode($result);
                    break;

                // case 'add_employee':
                //     $data = [
                //         'name' => $this->input->post('name'),
                //         'email' => $this->input->post('email'),
                //         'department_code' => $this->input->post('department_code'),
                //         'is_active' => $this->input->post('is_active') ? 1 : 0,
                //         'company_owned_by' => $this->input->post('company_owned_by') ?? 'Both'
                //     ];
                //     $result = $this->Admin_model->addEmployee($data);
                //     echo json_encode($result);
                //     break;

                case 'add_employee':
                    $data = [
                        'name' => trim($this->input->post('name')),
                        'email' => trim($this->input->post('email')),
                        'phone_number' => trim($this->input->post('phone_number')) ?: null,  // ADDED
                        'position' => trim($this->input->post('position')) ?: null,          // ADDED
                        'department_code' => $this->input->post('department_code'),
                        'is_active' => $this->input->post('is_active') ? 1 : 0,
                        'company_owned_by' => $this->input->post('company_owned_by') ?? 'Both'
                    ];
                    $result = $this->Admin_model->addEmployee($data);
                    echo json_encode($result);
                    break;
                    
                // case 'add_employee':
                //     $data = [
                //         'name' => $this->input->post('name'),
                //         'email' => $this->input->post('email'),
                //         'department_code' => $this->input->post('department_code'),
                //         'is_active' => $this->input->post('is_active') ? 1 : 0
                //     ];
                //     $result = $this->Admin_model->addEmployee($data);
                //     echo json_encode($result);
                //     break;
                    
                case 'toggle_employee_status':
                    $employee_id = $this->input->post('employee_id');
                    $new_status = (int)$this->input->post('new_status');
                    $result = $this->Admin_model->toggleEmployeeStatus($employee_id, $new_status);
                    echo json_encode($result);
                    break;
                    
                // case 'add_department':
                //     $data = [
                //         'department_code' => $this->input->post('department_code'),
                //         'name' => $this->input->post('name'),
                //         'description' => $this->input->post('description') ?? ''
                //     ];
                //     $result = $this->Admin_model->addDepartment($data);
                //     echo json_encode($result);
                //     break;
                    
                case 'get_all_purposes':
                    echo json_encode($this->Admin_model->getAllPurposes());
                    break;
                    
                // case 'add_purpose':
                //     $data = [
                //         'purpose_code' => strtolower(trim($this->input->post('purpose_code'))),
                //         'purpose_name' => trim($this->input->post('purpose_name')),
                //         'icon_class' => trim($this->input->post('icon_class') ?? 'bi-circle'),
                //         'color_class' => trim($this->input->post('color_class') ?? 'text-primary'),
                //         'is_active' => $this->input->post('is_active') ? 1 : 0
                //     ];
                //     $result = $this->Admin_model->addPurpose($data);
                //     echo json_encode($result);
                //     break;
                    
                case 'toggle_purpose_status':
                    $purpose_id = (int)$this->input->post('purpose_id');
                    $new_status = (int)$this->input->post('new_status');
                    $result = $this->Admin_model->togglePurposeStatus($purpose_id, $new_status);
                    echo json_encode($result);
                    break;

                // case 'auto_checkout_expired':
                //     echo json_encode($this->Admin_model->autoCheckoutExpiredVisits($companyFilter));
                //     break;

                case 'get_purpose':
                    $purpose_id = $this->input->get('purpose_id');
                    echo json_encode($this->Admin_model->getPurposeById($purpose_id));
                    break;
                    
                // case 'update_purpose':
                //     $data = [
                //         'purpose_id' => (int)$this->input->post('purpose_id'),
                //         'purpose_name' => trim($this->input->post('purpose_name')),
                //         'icon_class' => trim($this->input->post('icon_class') ?? 'bi-circle'),
                //         'color_class' => trim($this->input->post('color_class') ?? 'text-primary'),
                //         'company_owned_by' => trim($this->input->post('company_owned_by') ?? 'Both'),
                //         'is_active' => $this->input->post('is_active') ? 1 : 0
                //     ];
                    
                    // // Check permissions
                    // $canEdit = $this->Admin_model->canEditPurpose($data['purpose_id'], $companyFilter);
                    // if (!$canEdit['can_edit']) {
                    //     echo json_encode(['status' => 'error', 'message' => 'You do not have permission to edit this purpose']);
                    //     break;
                    // }
                    
                    // $result = $this->Admin_model->updatePurpose($data);
                    // echo json_encode($result);
                    // break;
                    
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

                case 'add_department':
                    $data = [
                        'department_code' => $this->input->post('department_code'),
                        'name' => $this->input->post('name'),
                        'name_en' => $this->input->post('name_en') ?: null,
                        'name_zh_tw' => $this->input->post('name_zh_tw') ?: null,
                        'name_zh_cn' => $this->input->post('name_zh_cn') ?: null,
                        'name_fil' => $this->input->post('name_fil') ?: null,
                        'name_ja' => $this->input->post('name_ja') ?: null,
                        'description' => $this->input->post('description') ?? '',
                        'is_active' => $this->input->post('is_active') ? 1 : 0
                    ];
                    $result = $this->Admin_model->addDepartment($data);
                    echo json_encode($result);
                    break;
                    
                case 'get_department':
                    $department_code = $this->input->get('department_code');
                    echo json_encode($this->Admin_model->getDepartmentById($department_code));
                    break;
                    
                case 'update_department':
                    $data = [
                        'department_code' => $this->input->post('department_code'),
                        'name' => $this->input->post('name'),
                        'name_en' => $this->input->post('name_en') ?: null,
                        'name_zh_tw' => $this->input->post('name_zh_tw') ?: null,
                        'name_zh_cn' => $this->input->post('name_zh_cn') ?: null,
                        'name_fil' => $this->input->post('name_fil') ?: null,
                        'name_ja' => $this->input->post('name_ja') ?: null,
                        'description' => $this->input->post('description') ?? '',
                        'is_active' => $this->input->post('is_active') ? 1 : 0

                    ];
                    $result = $this->Admin_model->updateDepartment($data);
                    echo json_encode($result);
                    break;

                // Add this new case after 'update_department':
                case 'toggle_department_status':
                    $department_code = $this->input->post('department_code');
                    $new_status = (int)$this->input->post('new_status');
                    $result = $this->Admin_model->toggleDepartmentStatus($department_code, $new_status);
                    echo json_encode($result);
                    break;
                    
                case 'add_purpose':
                    $data = [
                        'purpose_code' => strtolower(trim($this->input->post('purpose_code'))),
                        'purpose_name' => trim($this->input->post('purpose_name')),
                        'name_en' => trim($this->input->post('name_en')) ?: null,
                        'name_zh_tw' => trim($this->input->post('name_zh_tw')) ?: null,
                        'name_zh_cn' => trim($this->input->post('name_zh_cn')) ?: null,
                        'name_fil' => trim($this->input->post('name_fil')) ?: null,
                        'name_ja' => trim($this->input->post('name_ja')) ?: null,
                        'icon_class' => trim($this->input->post('icon_class') ?? 'bi-circle'),
                        'color_class' => trim($this->input->post('color_class') ?? 'text-primary'),
                        'company_owned_by' => trim($this->input->post('company_owned_by') ?? 'Both'),
                        'is_active' => $this->input->post('is_active') ? 1 : 0
                    ];
                    $result = $this->Admin_model->addPurpose($data);
                    echo json_encode($result);
                    break;
                    
                case 'update_purpose':
                    $data = [
                        'purpose_id' => (int)$this->input->post('purpose_id'),
                        'purpose_name' => trim($this->input->post('purpose_name')),
                        'name_en' => trim($this->input->post('name_en')) ?: null,
                        'name_zh_tw' => trim($this->input->post('name_zh_tw')) ?: null,
                        'name_zh_cn' => trim($this->input->post('name_zh_cn')) ?: null,
                        'name_fil' => trim($this->input->post('name_fil')) ?: null,
                        'name_ja' => trim($this->input->post('name_ja')) ?: null,
                        'icon_class' => trim($this->input->post('icon_class') ?? 'bi-circle'),
                        'color_class' => trim($this->input->post('color_class') ?? 'text-primary'),
                        'company_owned_by' => trim($this->input->post('company_owned_by') ?? 'Both'),
                        'is_active' => $this->input->post('is_active') ? 1 : 0
                    ];
                    
                    // Check permissions
                    $canEdit = $this->Admin_model->canEditPurpose($data['purpose_id'], $companyFilter);
                    if (!$canEdit['can_edit']) {
                        echo json_encode(['status' => 'error', 'message' => 'You do not have permission to edit this purpose']);
                        break;
                    }
                    
                    $result = $this->Admin_model->updatePurpose($data);
                    echo json_encode($result);
                    break;

                // Add this case inside the switch statement in ajax_handler() method
                // Place it after the 'update_department' case

                case 'toggle_department_status':
                    $department_code = $this->input->post('department_code');
                    $new_status = (int)$this->input->post('new_status');
                    $result = $this->Admin_model->toggleDepartmentStatus($department_code, $new_status);
                    echo json_encode($result);
                    break;


                    
                case 'get_report_types':
                    echo json_encode($this->Admin_model->getReportTypes());
                    break;

                case 'generate_report':
                    $reportType = $this->input->post('report_type');
                    $filters = [
                        'date_from' => $this->input->post('date_from'),
                        'date_to' => $this->input->post('date_to'),
                        'company_filter' => $companyFilter,
                        'department_code' => $this->input->post('department_code'),
                        'visitor_type' => $this->input->post('visitor_type')
                    ];
                    
                    switch($reportType) {
                        case 'department':
                            echo json_encode($this->Admin_model->getDepartmentReport($filters));
                            break;
                        case 'employee_visits':
                            echo json_encode($this->Admin_model->getEmployeeVisitsReport($filters));
                            break;
                        case 'visitor_visits':
                            echo json_encode($this->Admin_model->getVisitorVisitsReport($filters));
                            break;
                        case 'purposes':
                            echo json_encode($this->Admin_model->getPurposesReport($filters));
                            break;
                        case 'daily':
                            echo json_encode($this->Admin_model->getDailyReport($filters));
                            break;
                        case 'weekly':
                            echo json_encode($this->Admin_model->getWeeklyReport($filters));
                            break;
                        case 'monthly':
                            echo json_encode($this->Admin_model->getMonthlyReport($filters));
                            break;
                        case 'annual':
                            echo json_encode($this->Admin_model->getAnnualReport($filters));
                            break;
                        default:
                            echo json_encode(['status' => 'error', 'message' => 'Invalid report type']);
                    }
                    break;

                case 'get_company_comparison':
                    $filters = [
                        'date_from' => $this->input->get('date_from'),
                        'date_to' => $this->input->get('date_to')
                    ];
                    echo json_encode($this->Admin_model->getCompanyComparisonReport($filters));
                    break;

                // ============================================
                // ADD TO Admin.php ajax_handler() switch statement
                // ============================================

                case 'bulk_checkout':
                    $visit_ids = $this->input->post('visit_ids');
                    
                    // Handle JSON string or array
                    if (is_string($visit_ids)) {
                        $visit_ids = json_decode($visit_ids, true);
                    }
                    
                    if (empty($visit_ids) || !is_array($visit_ids)) {
                        echo json_encode(['success' => false, 'error' => 'No visits selected']);
                        break;
                    }
                    
                    $result = $this->Admin_model->bulkCheckoutVisits($visit_ids);
                    echo json_encode($result);
                    break;

                case 'checkout_all_active':
                    $result = $this->Admin_model->checkoutAllActiveVisits($companyFilter);
                    echo json_encode($result);
                    break;

                case 'notify_it_department':
                    // Validate required fields
                    $category = trim($this->input->post('category'));
                    $subject = trim($this->input->post('subject'));
                    $message = trim($this->input->post('message'));
                    $priority = trim($this->input->post('priority'));
                    
                    if (empty($category) || empty($subject) || empty($message)) {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Please fill in all required fields.'
                        ]);
                        break;
                    }
                    
                    // Get user info with fallbacks
                    $admin_name = $this->session->userdata('username') ?: 'Admin User';
                    $admin_email = $this->session->userdata('email') ?: 'admin@system.local';
                    $company_filter = $this->data['companyFilter'];
                    
                    // Generate ticket ID
                    $ticket_id = 'IT-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
                    
                    // Prepare ticket data
                    $ticket_data = [
                        'ticket_id' => $ticket_id,
                        'category' => $category,
                        'subject' => $subject,
                        'message' => $message,
                        'priority' => $priority,
                        'submitted_by' => $admin_name,
                        'submitter_email' => $admin_email,
                        'company' => $company_filter ?: 'Super Admin',
                        'status' => 'Open',
                        'submitted_at' => date('Y-m-d H:i:s'),
                        'ip_address' => $this->input->ip_address()
                    ];
                    
                    // Save to database
                    $db_saved = false;
                    try {
                        if ($this->db->table_exists('support_tickets')) {
                            $this->db->insert('support_tickets', $ticket_data);
                            $db_saved = true;
                        }
                    } catch (Exception $e) {
                        log_message('error', 'DB Error: ' . $e->getMessage());
                    }
                    
                    // Log the ticket
                    log_message('info', "IT Support Ticket: [{$priority}] {$subject} by {$admin_name}");
                    
                    // Return success
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Your notification has been logged and will be sent to the IT Department.',
                        'ticket_id' => $ticket_id
                    ]);
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