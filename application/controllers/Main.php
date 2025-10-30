<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
    
    // Constructor to load helpers
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url'); // Load URL helper for base_url()
        // $this->load->helper('breadcrumb'); // Load the breadcrumb helper if needed
    }
    
    // Template helper method for TW (Toms World) - WITH header and footer
    private function render_template_tw($content_view, $data = array()) {
        // Set default data that all pages need
        $default_data = array(
            'company' => 'TOMS WORLD',
            'company_code' => 'tw'
        );
        
        // Uncomment these if you have these models and methods
        // $default_data['permissionAnalytics'] = $this->main_model->getPermissionAnalytics();
        // $default_data['permissionSupplementary'] = $this->main_model->getPermissionSupplementary();
        // $default_data['permissionSolutions'] = $this->main_model->getPermissionSolutionsManagement();
        
        // Merge with passed data
        $data = array_merge($default_data, $data);
        
        // Load views WITH header and footer for TW
        $this->load->view('partials/__header_tw', $data);
        $this->load->view($content_view, $data);
        $this->load->view('partials/__footer', $data);
    }

    // Template helper method for PA (Pan Asia) - WITH header and footer
    private function render_template_pa($content_view, $data = array()) {
        // Set default data that all pages need
        $default_data = array(
            'company' => 'PAN-ASIA INTERNATIONAL',
            'company_code' => 'pa'
        );
        
        // Merge with passed data
        $data = array_merge($default_data, $data);
        
        // Load views WITH header and footer for PA
        $this->load->view('partials/__header_pa', $data);
        $this->load->view($content_view, $data);
        $this->load->view('partials/__footer', $data);
    }

    // Template helper method for Admin - NO header and footer
    private function render_template_admin($content_view, $data = array()) {
        // Get current method name for breadcrumb if needed
        $current_page = $this->router->fetch_method();
        
        // Add breadcrumb data
        $data['current_page'] = $current_page;
        // $data['breadcrumb_html'] = generate_breadcrumb($current_page); // Uncomment if using breadcrumb
        
        // Load ONLY the content view - NO header and footer for admin
        $this->load->view($content_view, $data);
    }

    // Template helper method for Login - NO header and footer
    private function render_template_login($content_view, $data = array()) {
        // Get current method name if needed
        $current_page = $this->router->fetch_method();
        $data['current_page'] = $current_page;
        
        // Load ONLY the content view - NO header and footer for login
        $this->load->view($content_view, $data);
    }
    
    // Generic template without header/footer (for debugging or special cases)
    private function render_template_without_header_footer($content_view, $data = array()) {
        // Load ONLY the content view
        $this->load->view($content_view, $data);
    }

    // ==================== PUBLIC METHODS ====================
    
    // Welcome/Index page
    public function index() {
        $this->load->view('welcome_message');
    }

    // Admin page - NO header/footer, handles login redirect
    public function admin() {
        // Check if user is already logged in
        if ($this->session->userdata('logged_in')) {
            // If logged in, redirect based on role
            $role = $this->session->userdata('role');
            if ($role == 'admin' || $role == 'manager') {
                redirect('visitor'); // Admin dashboard
            } else {
                redirect('visitor/checkin'); // Reception/Security check-in page
            }
        } else {
            // Not logged in, show admin page or redirect to login
            // Option 1: Show admin page without header/footer
            $this->render_template_admin('main/admin');
            
            // Option 2: Redirect to login (uncomment if preferred)
            // redirect('auth/login');
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

    // Login page - NO header/footer
    public function login() {
        $data = array(
            'page_title' => 'Login'
        );
        $this->render_template_login('auth/login', $data);
    }
    
    // Additional admin-related pages that don't need header/footer
    public function admin_dashboard() {
        // Check authentication first
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }
        
        $data = array(
            'page_title' => 'Admin Dashboard'
        );
        $this->render_template_admin('main/admin_dashboard', $data);
    }
}

?>