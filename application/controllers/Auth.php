<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'security']);
        $this->load->database();
    }
    
    // Login page
    public function index() {
        // Redirect to login
        redirect('auth/login');
    }
    
    // Login function
    public function login() {
        // If already logged in, redirect based on role
        if ($this->session->userdata('logged_in')) {
            $this->redirect_by_role();
            return;
        }
        
        if ($this->input->post()) {
            // Set validation rules
            $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'required');
            
            if ($this->form_validation->run() === TRUE) {
                $username = $this->input->post('username', TRUE);
                $password = $this->input->post('password', TRUE);
                $remember = $this->input->post('remember');
                
                // Check user credentials
                $user = $this->authenticate_user($username, $password);
                
                if ($user) {
                    // Set session data
                    $session_data = [
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'full_name' => $user['full_name'],
                        'role' => $user['role'],
                        'logged_in' => TRUE,
                        'login_time' => time()
                    ];
                    
                    $this->session->set_userdata($session_data);
                    
                    // Update last login
                    $this->db->where('id', $user['id']);
                    $this->db->update('users', [
                        'last_login' => date('Y-m-d H:i:s'),
                        'last_ip' => $this->input->ip_address()
                    ]);
                    
                    // Set remember me cookie if checked
                    if ($remember) {
                        $this->set_remember_me($user['id']);
                    }
                    
                    // Log successful login
                    $this->log_activity($user['id'], 'login', 'User logged in successfully');
                    
                    // Redirect based on role
                    $this->redirect_by_role();
                    
                } else {
                    // Log failed login attempt
                    $this->log_failed_attempt($username);
                    
                    $this->session->set_flashdata('error', 'Invalid username or password');
                    redirect('auth/login');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }
        
        // Load login view
        $this->load->view('auth/login');
    }
    
    // Authenticate user
    private function authenticate_user($username, $password) {
        // Check if user exists (by username or email)
        $this->db->where('username', $username);
        $this->db->or_where('email', $username);
        $this->db->where('is_active', 1);
        $user = $this->db->get('users')->row_array();
        
        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        
        return false;
    }
    
    // Redirect based on user role
    private function redirect_by_role() {
        $role = $this->session->userdata('role');
        
        switch($role) {
            case 'admin':
            case 'manager':
                redirect('visitor'); // Main admin dashboard
                break;
            case 'security':
            case 'reception':
                redirect('visitor/checkin'); // Check-in page
                break;
            default:
                redirect('main'); // Default page
                break;
        }
    }
    
    // Logout
    public function logout() {
        $user_id = $this->session->userdata('user_id');
        
        if ($user_id) {
            // Log logout activity
            $this->log_activity($user_id, 'logout', 'User logged out');
        }
        
        // Clear session
        $this->session->unset_userdata(['user_id', 'username', 'email', 'full_name', 'role', 'logged_in']);
        $this->session->sess_destroy();
        
        // Clear remember me cookie
        delete_cookie('remember_me');
        
        $this->session->set_flashdata('success', 'You have been logged out successfully');
        redirect('auth/login');
    }
    
    // Password reset request
    public function forgot_password() {
        if ($this->input->post()) {
            $email = $this->input->post('email', TRUE);
            
            // Check if email exists
            $user = $this->db->get_where('users', ['email' => $email, 'is_active' => 1])->row_array();
            
            if ($user) {
                // Generate reset token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Store reset token
                $this->db->insert('password_resets', [
                    'email' => $email,
                    'token' => $token,
                    'expires_at' => $expires
                ]);
                
                // Send reset email (implement email sending)
                $this->send_reset_email($email, $token);
                
                echo json_encode(['success' => true, 'message' => 'Password reset link sent to your email']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Email not found']);
            }
        }
    }
    
    // Reset password
    public function reset_password($token = null) {
        if (!$token) {
            show_404();
        }
        
        // Verify token
        $this->db->where('token', $token);
        $this->db->where('expires_at >', date('Y-m-d H:i:s'));
        $reset = $this->db->get('password_resets')->row_array();
        
        if (!$reset) {
            $this->session->set_flashdata('error', 'Invalid or expired reset token');
            redirect('auth/login');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
            
            if ($this->form_validation->run() === TRUE) {
                $new_password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                
                // Update password
                $this->db->where('email', $reset['email']);
                $this->db->update('users', ['password' => $new_password]);
                
                // Delete used token
                $this->db->delete('password_resets', ['id' => $reset['id']]);
                
                $this->session->set_flashdata('success', 'Password reset successfully. Please login with your new password.');
                redirect('auth/login');
            }
        }
        
        $data['token'] = $token;
        $this->load->view('auth/reset_password', $data);
    }
    
    // Set remember me cookie
    private function set_remember_me($user_id) {
        $token = bin2hex(random_bytes(32));
        
        // Store token in database
        $this->db->insert('remember_tokens', [
            'user_id' => $user_id,
            'token' => $token,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days'))
        ]);
        
        // Set cookie
        set_cookie('remember_me', $token, 86400 * 30); // 30 days
    }
    
    // Check remember me
    public function check_remember_me() {
        $token = get_cookie('remember_me');
        
        if ($token) {
            $this->db->where('token', $token);
            $this->db->where('expires_at >', date('Y-m-d H:i:s'));
            $remember = $this->db->get('remember_tokens')->row_array();
            
            if ($remember) {
                // Auto login
                $user = $this->db->get_where('users', ['id' => $remember['user_id']])->row_array();
                
                if ($user && $user['is_active']) {
                    // Set session
                    $session_data = [
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'full_name' => $user['full_name'],
                        'role' => $user['role'],
                        'logged_in' => TRUE
                    ];
                    
                    $this->session->set_userdata($session_data);
                    return true;
                }
            }
        }
        
        return false;
    }
    
    // Log activity
    private function log_activity($user_id, $action, $description) {
        $this->db->insert('activity_logs', [
            'user_id' => $user_id,
            'action' => $action,
            'description' => $description,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    // Log failed login attempt
    private function log_failed_attempt($username) {
        $this->db->insert('login_attempts', [
            'username' => $username,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'attempted_at' => date('Y-m-d H:i:s')
        ]);
        
        // Check for too many failed attempts
        $this->db->where('ip_address', $this->input->ip_address());
        $this->db->where('attempted_at >', date('Y-m-d H:i:s', strtotime('-15 minutes')));
        $attempts = $this->db->count_all_results('login_attempts');
        
        if ($attempts > 5) {
            // Implement IP blocking or CAPTCHA
            $this->session->set_flashdata('error', 'Too many failed attempts. Please try again later.');
        }
    }
    
    // Send reset email (placeholder - implement with your email service)
    private function send_reset_email($email, $token) {
        // Implement email sending logic
        // You can use CodeIgniter's email library or any third-party service
        
        $reset_link = base_url('auth/reset_password/' . $token);
        
        // Example with CI Email library
        /*
        $this->load->library('email');
        
        $this->email->from('noreply@yoursite.com', 'Visitor Management');
        $this->email->to($email);
        $this->email->subject('Password Reset Request');
        $this->email->message('Click here to reset your password: ' . $reset_link);
        
        $this->email->send();
        */
    }
}

// Additional database tables needed for auth system
/*
-- Add these tables to your database:

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(100) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_email (email)
);

CREATE TABLE remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(100) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token)
);

CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(50),
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_action (action)
);

CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    ip_address VARCHAR(45),
    user_agent TEXT,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip (ip_address),
    INDEX idx_attempted (attempted_at)
);

-- Add IP address field to users table
ALTER TABLE users ADD COLUMN last_ip VARCHAR(45) AFTER last_login;

-- Insert sample users
INSERT INTO users (username, email, password, full_name, role, is_active) VALUES
('admin', 'admin@tomsworld.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', 1),
('reception', 'reception@tomsworld.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Reception Staff', 'reception', 1),
('security', 'security@tomsworld.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Security Guard', 'security', 1);

-- Default password for all: password
*/