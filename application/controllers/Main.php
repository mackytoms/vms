<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
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

    // ==================== PUBLIC METHODS ====================
    
    // Welcome/Index page - Show login
    public function index() {
        // If already logged in, go to admin
        if ($this->session->userdata('logged_in')) {
            redirect('main/admin');
        }
        $this->load->view('auth/login');
    }

    // Admin page - Protected, requires login
    public function admin() {
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            // Not logged in, redirect to login page
            $this->session->set_flashdata('error', 'Please login to access admin panel');
            redirect('auth');
        }
        
        // User is logged in, show admin dashboard
        $data = array(
            'page_title' => 'Admin Dashboard',
            'user' => array(
                'user_id' => $this->session->userdata('user_id'),
                'username' => $this->session->userdata('username'),
                'full_name' => $this->session->userdata('full_name'),
                'email' => $this->session->userdata('email'),
                'role' => $this->session->userdata('role'),
                'company_access' => $this->session->userdata('company_access')
            )
        );
        
        $this->render_template_admin('main/admin', $data);
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
        $this->render_template_login('auth/login', $data);
    }
}