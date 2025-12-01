<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get user by username or email
     */
    public function get_user_by_username_or_email($username) {
        return $this->db
            ->where('username', $username)
            ->or_where('email', $username)
            ->get('users')
            ->row();
    }
    
    /**
     * Get user by ID
     */
    public function get_user_by_id($user_id) {
        return $this->db
            ->where('user_id', $user_id)
            ->get('users')
            ->row();
    }
    
    /**
     * Update last login timestamp
     */
    public function update_last_login($user_id) {
        return $this->db
            ->where('user_id', $user_id)
            ->update('users', ['last_login' => date('Y-m-d H:i:s')]);
    }
    
    /**
     * Increment login attempts
     */
    public function increment_login_attempts($user_id, $attempts) {
        return $this->db
            ->where('user_id', $user_id)
            ->update('users', ['login_attempts' => $attempts]);
    }
    
    /**
     * Reset login attempts
     */
    public function reset_login_attempts($user_id) {
        return $this->db
            ->where('user_id', $user_id)
            ->update('users', [
                'login_attempts' => 0,
                'locked_until' => NULL
            ]);
    }
    
    /**
     * Lock user account
     */
    public function lock_account($user_id, $locked_until) {
        return $this->db
            ->where('user_id', $user_id)
            ->update('users', [
                'login_attempts' => 5,
                'locked_until' => $locked_until
            ]);
    }
    
    /**
     * Log login attempt
     */
    public function log_login($data) {
        return $this->db->insert('login_logs', $data);
    }
    
    /**
     * Set remember token
     */
    public function set_remember_token($user_id, $token, $expires) {
        // Clear old sessions for this user
        $this->db->where('user_id', $user_id)->delete('user_sessions');
        
        // Insert new session
        return $this->db->insert('user_sessions', [
            'user_id' => $user_id,
            'token' => hash('sha256', $token),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'expires_at' => $expires
        ]);
    }
    
    /**
     * Get session by token
     */
    public function get_session_by_token($token) {
        return $this->db
            ->where('token', hash('sha256', $token))
            ->get('user_sessions')
            ->row();
    }
    
    /**
     * Clear remember token
     */
    public function clear_remember_token($user_id) {
        return $this->db
            ->where('user_id', $user_id)
            ->delete('user_sessions');
    }
    
    /**
     * Create new user
     */
    public function create_user($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->insert('users', $data);
    }
    
    /**
     * Update user password
     */
    public function update_password($user_id, $new_password) {
        return $this->db
            ->where('user_id', $user_id)
            ->update('users', [
                'password' => password_hash($new_password, PASSWORD_DEFAULT),
                'password_reset_token' => NULL,
                'password_reset_expires' => NULL
            ]);
    }
    
    /**
     * Set password reset token
     */
    public function set_reset_token($user_id, $token, $expires) {
        return $this->db
            ->where('user_id', $user_id)
            ->update('users', [
                'password_reset_token' => hash('sha256', $token),
                'password_reset_expires' => $expires
            ]);
    }
    
    /**
     * Get all users
     */
    public function get_all_users() {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->get('users')
            ->result();
    }
    
    /**
     * Update user
     */
    public function update_user($user_id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        
        return $this->db
            ->where('user_id', $user_id)
            ->update('users', $data);
    }
    
    /**
     * Delete user
     */
    public function delete_user($user_id) {
        return $this->db
            ->where('user_id', $user_id)
            ->delete('users');
    }
    
    /**
     * Get login logs
     */
    public function get_login_logs($limit = 100) {
        return $this->db
            ->select('login_logs.*, users.full_name')
            ->join('users', 'users.user_id = login_logs.user_id', 'left')
            ->order_by('login_logs.created_at', 'DESC')
            ->limit($limit)
            ->get('login_logs')
            ->result();
    }
}