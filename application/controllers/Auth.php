<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form', 'cookie']);
    }
    
    /**
     * Display login page
     */
    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('main/admin');
        }
        $this->load->view('auth/login');
    }
    
    /**
     * Process login
     */
    public function login() {
        if ($this->session->userdata('logged_in')) {
            redirect('main/admin');
        }
        
        if ($this->input->method() !== 'post') {
            redirect('auth');
        }
        
        $username = trim($this->input->post('username', TRUE));
        $password = $this->input->post('password');
        $remember = $this->input->post('remember') ? TRUE : FALSE;
        
        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Please enter username and password');
            redirect('auth');
        }
        
        // Check if user exists
        $user = $this->Auth_model->get_user_by_username_or_email($username);
        
        if (!$user) {
            $this->log_login_attempt(null, $username, 'failed', 'User not found');
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('auth');
        }
        
        // Check if account is locked
        if ($user->locked_until && strtotime($user->locked_until) > time()) {
            $remaining = ceil((strtotime($user->locked_until) - time()) / 60);
            $this->log_login_attempt($user->user_id, $username, 'locked', 'Account locked');
            $this->session->set_flashdata('error', "Account locked. Try again in {$remaining} minutes.");
            redirect('auth');
        }
        
        // Check if account is active
        if (!$user->is_active) {
            $this->log_login_attempt($user->user_id, $username, 'failed', 'Account inactive');
            $this->session->set_flashdata('error', 'Your account has been deactivated');
            redirect('auth');
        }
        
        // Password verification - supports both plain text and hashed
        $password_valid = false;
        
        // First try plain text comparison
        if ($password === $user->password) {
            $password_valid = true;
        }
        // Then try hash comparison
        elseif (password_verify($password, $user->password)) {
            $password_valid = true;
        }
        
        if (!$password_valid) {
            $this->handle_failed_login($user);
            $this->log_login_attempt($user->user_id, $username, 'failed', 'Wrong password');
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('auth');
        }
        
        // Success! Reset login attempts and set session
        $this->Auth_model->reset_login_attempts($user->user_id);
        $this->Auth_model->update_last_login($user->user_id);
        
        // Set session data
        $session_data = [
            'user_id' => $user->user_id,
            'username' => $user->username,
            'email' => $user->email,
            'full_name' => $user->full_name,
            'role' => $user->role,
            'company_access' => $user->company_access,
            'avatar' => $user->avatar,
            'logged_in' => TRUE
        ];
        $this->session->set_userdata($session_data);
        
        // Handle remember me
        if ($remember) {
            $this->set_remember_cookie($user->user_id);
        }
        
        $this->log_login_attempt($user->user_id, $username, 'success', null);
        
        // Redirect to admin panel
        redirect('main/admin');
    }
    
    /**
     * Logout user
     */
    public function logout() {
        // Get user id before destroying session
        $user_id = $this->session->userdata('user_id');
        
        // Clear remember token if exists
        if ($user_id) {
            $this->Auth_model->clear_remember_token($user_id);
        }
        
        // Delete remember cookie
        delete_cookie('remember_token');
        
        // Unset all session data
        $session_data = ['user_id', 'username', 'email', 'full_name', 'role', 'company_access', 'avatar', 'logged_in'];
        $this->session->unset_userdata($session_data);
        
        // Destroy session
        $this->session->sess_destroy();
        
        // Redirect to login with success message
        // Note: Since session is destroyed, we use a different approach
        redirect('auth?logout=success');
    }
    
    /**
     * Handle failed login attempt
     */
    private function handle_failed_login($user) {
        $attempts = $user->login_attempts + 1;
        $max_attempts = 5;
        $lockout_time = 15;
        
        if ($attempts >= $max_attempts) {
            $locked_until = date('Y-m-d H:i:s', strtotime("+{$lockout_time} minutes"));
            $this->Auth_model->lock_account($user->user_id, $locked_until);
        } else {
            $this->Auth_model->increment_login_attempts($user->user_id, $attempts);
        }
    }
    
    /**
     * Log login attempt
     */
    private function log_login_attempt($user_id, $username, $status, $reason) {
        $data = [
            'user_id' => $user_id,
            'username_attempted' => $username,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'status' => $status,
            'failure_reason' => $reason
        ];
        $this->Auth_model->log_login($data);
    }
    
    /**
     * Set remember me cookie
     */
    private function set_remember_cookie($user_id) {
        $token = bin2hex(random_bytes(32));
        $expires = time() + (30 * 24 * 60 * 60);
        
        $this->Auth_model->set_remember_token($user_id, $token, date('Y-m-d H:i:s', $expires));
        
        set_cookie([
            'name' => 'remember_token',
            'value' => $token,
            'expire' => 30 * 24 * 60 * 60,
            'httponly' => TRUE,
            'secure' => is_https()
        ]);
    }
}