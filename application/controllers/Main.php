<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
    
    // Constructor to load helpers
    public function __construct() {
        parent::__construct();
        $this->load->helper('url'); // Load URL helper for base_url()
        // $this->load->helper('breadcrumb'); // Load the breadcrumb helper
    }
    
    // Template helper method
    private function render_template($content_view, $data = array()) {
        // Set default data that all pages need (if you have these models)
        $default_data = array();
        
        // Uncomment these if you have these models and methods
        // $default_data = array(
        //     'permissionAnalytics' => $this->main_model->getPermissionAnalytics(),
        //     'permissionSupplementary' => $this->main_model->getPermissionSupplementary(),
        //     'permissionSolutions' => $this->main_model->getPermissionSolutionsManagement()
        // );
        
        // Merge with passed data
        $data = array_merge($default_data, $data);
        
        // Load views in order: header, content, footer
        $this->load->view('partials/__header', $data);
        $this->load->view($content_view, $data);
        $this->load->view('partials/__footer');
    }

    // Template helper method IS TEMPORARY
    // This is used for the login page to avoid loading header and footer
    private function render_template_user($content_view, $data = array()) {
        // Set default data that all pages need (if you have these models)
        $default_data = array();
        
        // Uncomment these if you have these models and methods
        // $default_data = array(
        //     'permissionAnalytics' => $this->main_model->getPermissionAnalytics(),
        //     'permissionSupplementary' => $this->main_model->getPermissionSupplementary(),
        //     'permissionSolutions' => $this->main_model->getPermissionSolutionsManagement()
        // );
        
        // Merge with passed data
        $data = array_merge($default_data, $data);
        
        // Load views in order: header, content, footer
        $this->load->view('partials/__header_user', $data);
        $this->load->view($content_view, $data);
        $this->load->view('partials/__footer');
    }

    // private function render_template_approver($content_view, $data = array()) {
    //     // Set default data that all pages need (if you have these models)
    //     $default_data = array();
        
    //     // Uncomment these if you have these models and methods
    //     // $default_data = array(
    //     //     'permissionAnalytics' => $this->main_model->getPermissionAnalytics(),
    //     //     'permissionSupplementary' => $this->main_model->getPermissionSupplementary(),
    //     //     'permissionSolutions' => $this->main_model->getPermissionSolutionsManagement()
    //     // );
        
    //     // Merge with passed data
    //     $data = array_merge($default_data, $data);
        
    //     // Load views in order: header, content, footer
    //     $this->load->view('partials/__header_approver', $data);
    //     $this->load->view($content_view, $data);
    //     $this->load->view('partials/__footer');
    // }


    // Update your render_template_admin method:
    private function render_template_admin($content_view, $data = array()) {
        // Get current method name for breadcrumb
        $current_page = $this->router->fetch_method();
        
        // Add breadcrumb data
        $data['current_page'] = $current_page;
        $data['breadcrumb_html'] = generate_breadcrumb($current_page);
        
        // Load views
        $this->load->view('partials/__header_admin', $data);
        $this->load->view($content_view, $data);
        $this->load->view('partials/__footer');
    }

    // Update your render_template_admin_event method:
    private function render_template_admin_event($content_view, $data = array()) {
        // Get current method name for breadcrumb
        $current_page = $this->router->fetch_method();
        
        // Add breadcrumb data
        $data['current_page'] = $current_page;
        $data['breadcrumb_html'] = generate_breadcrumb($current_page);
        
        // Load views
        $this->load->view('partials/__header_admin_event', $data);
        $this->load->view($content_view, $data);
        $this->load->view('partials/__footer');
    }

    // private function render_template_admin($content_view, $data = array()) {
    //     // Set default data that all pages need (if you have these models)
    //     $default_data = array();
        
    //     // Uncomment these if you have these models and methods
    //     // $default_data = array(
    //     //     'permissionAnalytics' => $this->main_model->getPermissionAnalytics(),
    //     //     'permissionSupplementary' => $this->main_model->getPermissionSupplementary(),
    //     //     'permissionSolutions' => $this->main_model->getPermissionSolutionsManagement()
    //     // );
        
    //     // Merge with passed data
    //     $data = array_merge($default_data, $data);
        
    //     // Load views in order: header, content, footer
    //     $this->load->view('partials/__header_admin', $data);
    //     $this->load->view($content_view, $data);
    //     $this->load->view('partials/__footer');
    // }
   

    // Template helper method for Debugging
    // This is used for the login page to avoid loading header and footer
    private function render_template_without_header_footer($content_view, $data = array()) {
        // Set default data that all pages need (if you have these models)
        $default_data = array();
        
        // Uncomment these if you have these models and methods
        // $default_data = array(
        //     'permissionAnalytics' => $this->main_model->getPermissionAnalytics(),
        //     'permissionSupplementary' => $this->main_model->getPermissionSupplementary(),
        //     'permissionSolutions' => $this->main_model->getPermissionSolutionsManagement()
        // );
        
        // Merge with passed data
        $data = array_merge($default_data, $data);
        
        // Load views in order: No header, content, No footer
        $this->load->view($content_view);
    }

    // INDEXES
    public function index() {
		$this->load->view('welcome_message');
        // $this->render_template_without_header_footer('tw');

        // Use the same template structure as other pages
        // $this->render_template_user('main/dashboard.php');
        // $this->render_template_without_header_footer('authenticator/auth-login');
    }

    public function admin(){
        $this->render_template_without_header_footer('main/admin');
    }

    public function tw(){
        $this->render_template_without_header_footer('main/tw');
    }
    
    public function pa(){
        $this->render_template_without_header_footer('main/pa');
    }
}

?>
