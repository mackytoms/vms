<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Dashboard Statistics
    public function getDashboardStats($companyFilter = null) {
        $stats = array();

        // Today's total visitors (ALL types - new AND returning)
        $this->db->select('COUNT(*) as today_total');
        $this->db->from('visits v');
        $this->db->join('visitors vis', 'v.visitor_id = vis.visitor_id', 'left');
        $this->db->where('DATE(v.check_in_time)', date('Y-m-d'));
        
        // Today's total visitors
        // $this->db->select('COUNT(DISTINCT visitor_id) as today_total');
        // $this->db->from('visits');
        // $this->db->where('DATE(check_in_time)', 'CURDATE()', false);
        if ($companyFilter) {
            $this->db->where('v.company_visited', $companyFilter);
        }
        $query = $this->db->get();
        $stats['today_total'] = $query->row()->today_total;
        
        // Currently in building
        $this->db->select('COUNT(*) as currently_in');
        $this->db->from('visits');
        $this->db->where('check_out_time IS NULL');
        if ($companyFilter) {
            $this->db->where('company_visited', $companyFilter);
        }
        $query = $this->db->get();
        $stats['currently_in'] = $query->row()->currently_in;
        
        // Average duration
        $this->db->select('AVG(TIMESTAMPDIFF(HOUR, check_in_time, IFNULL(check_out_time, NOW()))) as avg_duration');
        $this->db->from('visits');
        $this->db->where('DATE(check_in_time)', 'CURDATE()', false);
        if ($companyFilter) {
            $this->db->where('company_visited', $companyFilter);
        }
        $query = $this->db->get();
        $avg = $query->row()->avg_duration;
        $stats['avg_duration'] = $avg ? round($avg, 1) . 'h' : '0h';
        
        return $stats;
    }
    
    public function getRecentActivity($companyFilter = null) {
        $this->db->select('v.*, vi.first_name, vi.last_name, vi.company, e.name as host_name, v.company_visited, v.additional_notes');
        $this->db->from('visits v');
        $this->db->join('visitors vi', 'v.visitor_id = vi.visitor_id');
        $this->db->join('employees e', 'v.host_employee_id = e.employee_id');
        if ($companyFilter) {
            $this->db->where('v.company_visited', $companyFilter);
        }
        $this->db->order_by('v.check_in_time', 'DESC');
        $this->db->limit(10);
        
        return $this->db->get()->result_array();
    }
    
    public function getActiveVisits($companyFilter = null) {
        $this->db->select('v.*, vi.first_name, vi.last_name, vi.company, vi.email, vi.phone, vi.photo, e.name as host_name, d.name as department_name, v.company_visited, v.additional_notes');
        $this->db->from('visits v');
        $this->db->join('visitors vi', 'v.visitor_id = vi.visitor_id');
        $this->db->join('employees e', 'v.host_employee_id = e.employee_id');
        $this->db->join('departments d', 'e.department_code = d.department_code');
        $this->db->where('v.check_out_time IS NULL');
        if ($companyFilter) {
            $this->db->where('v.company_visited', $companyFilter);
        }
        
        return $this->db->get()->result_array();
    }
    
    public function getAllVisitors($companyFilter = null) {
        $this->db->select('vi.*, COUNT(v.visit_id) as total_visits, MAX(v.check_in_time) as last_visit');
        $this->db->from('visitors vi');
        $this->db->join('visits v', 'vi.visitor_id = v.visitor_id', 'left');
        
        if ($companyFilter) {
            $this->db->where('v.company_visited', $companyFilter);
        }
        
        $this->db->group_by('vi.visitor_id');
        
        if ($companyFilter) {
            $this->db->having('total_visits >', 0);
        }
        
        return $this->db->get()->result_array();
    }
    
    public function getDashboardStatsByCompany($companyFilter = null) {
        $stats = array();
        
        if ($companyFilter === 'Toms World' || $companyFilter === null) {
            // Toms World today
            $this->db->select('COUNT(DISTINCT visitor_id) as count');
            $this->db->from('visits');
            $this->db->where('DATE(check_in_time)', 'CURDATE()', false);
            $this->db->where('company_visited', 'Toms World');
            $query = $this->db->get();
            $stats['toms_world_today'] = $query->row()->count;
            
            // Toms World active
            $this->db->select('COUNT(*) as count');
            $this->db->from('visits');
            $this->db->where('check_out_time IS NULL');
            $this->db->where('company_visited', 'Toms World');
            $query = $this->db->get();
            $stats['toms_world_active'] = $query->row()->count;
        }
        
        if ($companyFilter === 'Pan Asia' || $companyFilter === null) {
            // Pan Asia today
            $this->db->select('COUNT(DISTINCT visitor_id) as count');
            $this->db->from('visits');
            $this->db->where('DATE(check_in_time)', 'CURDATE()', false);
            $this->db->where('company_visited', 'Pan Asia');
            $query = $this->db->get();
            $stats['pan_asia_today'] = $query->row()->count;
            
            // Pan Asia active
            $this->db->select('COUNT(*) as count');
            $this->db->from('visits');
            $this->db->where('check_out_time IS NULL');
            $this->db->where('company_visited', 'Pan Asia');
            $query = $this->db->get();
            $stats['pan_asia_active'] = $query->row()->count;
        }
        
        return $stats;
    }
    
    // // Employee Management
    // public function getEmployees() {
    //     $this->db->select('e.*, d.name as department_name, COUNT(v.visit_id) as total_visits');
    //     $this->db->from('employees e');
    //     $this->db->join('departments d', 'e.department_code = d.department_code');
    //     $this->db->join('visits v', 'e.employee_id = v.host_employee_id', 'left');
    //     $this->db->group_by('e.employee_id');
        
    //     return $this->db->get()->result_array();
    // }

    // Modify getEmployees() to filter by company
    public function getEmployees($companyFilter = null) {
        $this->db->select('e.*, d.name as department_name, COUNT(v.visit_id) as total_visits');
        $this->db->from('employees e');
        $this->db->join('departments d', 'e.department_code = d.department_code');
        $this->db->join('visits v', 'e.employee_id = v.host_employee_id', 'left');
        
        if ($companyFilter) {
            $this->db->group_start();
            $this->db->where('e.company_owned_by', $companyFilter);
            $this->db->or_where('e.company_owned_by', 'Both');
            $this->db->group_end();
        }
        
        $this->db->group_by('e.employee_id');
        
        return $this->db->get()->result_array();
    }

    // Also update getEmployeeById to return phone_number
    public function getEmployeeById($employee_id) {
        $this->db->select('e.*, d.name as department_name');
        $this->db->from('employees e');
        $this->db->join('departments d', 'e.department_code = d.department_code');
        $this->db->where('e.employee_id', $employee_id);
        
        $result = $this->db->get()->row_array();
        
        if ($result) {
            return ['status' => 'success', 'employee' => $result];
        }
        
        return ['status' => 'error', 'message' => 'Employee not found'];
    }

    // Add method to check if employee can be edited
    public function canEditEmployee($employee_id, $companyFilter) {
        $this->db->where('employee_id', $employee_id);
        $employee = $this->db->get('employees')->row_array();
        
        if (!$employee) {
            return ['can_edit' => false, 'reason' => 'Employee not found'];
        }
        
        // Super admin can edit everything
        if ($companyFilter === null) {
            return ['can_edit' => true];
        }
        
        // Both companies can be edited by any admin
        if ($employee['company_owned_by'] === 'Both') {
            return ['can_edit' => true];
        }
        
        // Check if the employee belongs to the admin's company
        if ($employee['company_owned_by'] === $companyFilter) {
            return ['can_edit' => true];
        }
        
        return ['can_edit' => false, 'reason' => 'This employee belongs to another company'];
    }
    
    public function addEmployee($data) {
        // Generate employee ID
        $this->db->select('COUNT(*) as cnt');
        $this->db->from('employees');
        $this->db->where('department_code', $data['department_code']);
        $count = $this->db->get()->row()->cnt + 1;
        
        $employee_id = $data['department_code'] . str_pad($count, 3, '0', STR_PAD_LEFT);
        
        $insert_data = [
            'employee_id' => $employee_id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'] ?? null,    // Use phone_number (matches form)
            'position' => $data['position'] ?? null,
            'department_code' => $data['department_code'],
            'is_active' => $data['is_active'],
            'company_owned_by' => $data['company_owned_by'] ?? 'Both',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->db->insert('employees', $insert_data)) {
            return ['success' => true, 'employee_id' => $employee_id];
        }
        
        return ['success' => false, 'error' => $this->db->error()['message']];
    }

    public function updateEmployee($data) {
        $update_data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'] ?? null,    // Use phone_number (matches form)
            'position' => $data['position'] ?? null,
            'department_code' => $data['department_code'],
            'company_owned_by' => $data['company_owned_by'],
            'is_active' => $data['is_active'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('employee_id', $data['employee_id']);
        
        if ($this->db->update('employees', $update_data)) {
            return ['status' => 'success'];
        }
        
        return ['status' => 'error', 'message' => $this->db->error()['message']];
    }

    
    public function toggleEmployeeStatus($employee_id, $new_status) {
        $this->db->where('employee_id', $employee_id);
        if ($this->db->update('employees', ['is_active' => $new_status])) {
            return ['success' => true, 'new_status' => $new_status];
        }
        
        return ['success' => false, 'error' => $this->db->error()['message']];
    }
    
    public function getEmployeeVisitHistory($employee_id) {
        $this->db->select('v.*, vi.first_name, vi.last_name, vi.company, vi.email, vi.phone');
        $this->db->from('visits v');
        $this->db->join('visitors vi', 'v.visitor_id = vi.visitor_id');
        $this->db->where('v.host_employee_id', $employee_id);
        $this->db->order_by('v.check_in_time', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    // Department Management
    public function getDepartments() {
        $this->db->select('d.*, COUNT(DISTINCT e.employee_id) as employee_count, COUNT(DISTINCT v.visit_id) as visit_count');
        $this->db->from('departments d');
        $this->db->join('employees e', 'd.department_code = e.department_code', 'left');
        $this->db->join('visits v', 'e.employee_id = v.host_employee_id', 'left');
        $this->db->group_by('d.department_code');
        
        return $this->db->get()->result_array();
    }
    
    // public function addDepartment($data) {
    //     $insert_data = [
    //         'department_code' => $data['department_code'],
    //         'name' => $data['name'],
    //         'description' => $data['description'],
    //         'created_at' => date('Y-m-d H:i:s')
    //     ];
        
    //     if ($this->db->insert('departments', $insert_data)) {
    //         return ['success' => true];
    //     }
        
    //     return ['success' => false, 'error' => $this->db->error()['message']];
    // }

    // public function addDepartment($data) {
    //     $insert_data = [
    //         'department_code' => $data['department_code'],
    //         'name' => $data['name'],
    //         'name_en' => $data['name_en'] ?? null,
    //         'name_zh_tw' => $data['name_zh_tw'] ?? null,
    //         'name_zh_cn' => $data['name_zh_cn'] ?? null,
    //         'name_fil' => $data['name_fil'] ?? null,
    //         'name_ja' => $data['name_ja'] ?? null,
    //         'description' => $data['description'] ?? '',
    //         'created_at' => date('Y-m-d H:i:s')
    //     ];
        
    //     if ($this->db->insert('departments', $insert_data)) {
    //         return ['success' => true];
    //     }
        
    //     return ['success' => false, 'error' => $this->db->error()['message']];
    // }

    public function addDepartment($data) {
        $insert_data = [
            'department_code' => $data['department_code'],
            'name' => $data['name'],
            'name_en' => $data['name_en'] ?? null,
            'name_zh_tw' => $data['name_zh_tw'] ?? null,
            'name_zh_cn' => $data['name_zh_cn'] ?? null,
            'name_fil' => $data['name_fil'] ?? null,
            'name_ja' => $data['name_ja'] ?? null,
            'description' => $data['description'] ?? '',
            'is_active' => $data['is_active'] ?? 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->db->insert('departments', $insert_data)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => $this->db->error()['message']];
    }

    public function getDepartmentById($department_code) {
        $this->db->where('department_code', $department_code);
        $department = $this->db->get('departments')->row_array();
        
        if ($department) {
            return ['status' => 'success', 'department' => $department];
        }
        
        return ['status' => 'error', 'message' => 'Department not found'];
    }

    // public function updateDepartment($data) {
    //     $update_data = [
    //         'name' => $data['name'],
    //         'name_en' => $data['name_en'] ?? null,
    //         'name_zh_tw' => $data['name_zh_tw'] ?? null,
    //         'name_zh_cn' => $data['name_zh_cn'] ?? null,
    //         'name_fil' => $data['name_fil'] ?? null,
    //         'name_ja' => $data['name_ja'] ?? null,
    //         'description' => $data['description'] ?? ''
    //     ];
        
    //     $this->db->where('department_code', $data['department_code']);
        
    //     if ($this->db->update('departments', $update_data)) {
    //         return ['status' => 'success'];
    //     }
        
    //     return ['status' => 'error', 'message' => $this->db->error()['message']];
    // }

    public function updateDepartment($data) {
        $update_data = [
            'name' => $data['name'],
            'name_en' => $data['name_en'] ?? null,
            'name_zh_tw' => $data['name_zh_tw'] ?? null,
            'name_zh_cn' => $data['name_zh_cn'] ?? null,
            'name_fil' => $data['name_fil'] ?? null,
            'name_ja' => $data['name_ja'] ?? null,
            'description' => $data['description'] ?? '',
            'is_active' => $data['is_active'] ?? 1
        ];
        
        $this->db->where('department_code', $data['department_code']);
        
        if ($this->db->update('departments', $update_data)) {
            return ['status' => 'success'];
        }
        
        return ['status' => 'error', 'message' => $this->db->error()['message']];
    }

    // Add this method to Admin_model.php after the updateDepartment method

    public function toggleDepartmentStatus($department_code, $new_status) {
        $this->db->where('department_code', $department_code);
        if ($this->db->update('departments', ['is_active' => $new_status])) {
            return ['status' => 'success', 'new_status' => $new_status];
        }
        
        return ['status' => 'error', 'message' => $this->db->error()['message']];
    }
    
    public function getEmployeesByDepartment($department_code) {
        $this->db->select('e.*, d.name as department_name, COUNT(v.visit_id) as total_visits');
        $this->db->from('employees e');
        $this->db->join('departments d', 'e.department_code = d.department_code');
        $this->db->join('visits v', 'e.employee_id = v.host_employee_id', 'left');
        $this->db->where('e.department_code', $department_code);
        $this->db->group_by('e.employee_id');
        $this->db->order_by('e.name');
        
        return $this->db->get()->result_array();
    }
    
    // Visitor Management
    public function getVisitorById($visitor_id) {
        $this->db->select('vi.*, COUNT(v.visit_id) as total_visits, MAX(v.check_in_time) as last_visit');
        $this->db->from('visitors vi');
        $this->db->join('visits v', 'vi.visitor_id = v.visitor_id', 'left');
        $this->db->where('vi.visitor_id', $visitor_id);
        $this->db->group_by('vi.visitor_id');
        
        $result = $this->db->get()->row_array();
        return $result ?: null;
    }
    
    public function getVisitById($visit_id) {
        $this->db->select('v.*, vi.first_name, vi.last_name, vi.company, vi.email, vi.phone, vi.photo, e.name as host_name, d.name as department_name, v.company_visited');
        $this->db->from('visits v');
        $this->db->join('visitors vi', 'v.visitor_id = vi.visitor_id');
        $this->db->join('employees e', 'v.host_employee_id = e.employee_id');
        $this->db->join('departments d', 'e.department_code = d.department_code');
        $this->db->where('v.visit_id', $visit_id);
        
        $result = $this->db->get()->row_array();
        return $result ?: null;
    }
    
    public function getVisitorHistory($visitor_id) {
        $this->db->select('v.*, e.name as host_name, d.name as department_name');
        $this->db->from('visits v');
        $this->db->join('employees e', 'v.host_employee_id = e.employee_id');
        $this->db->join('departments d', 'e.department_code = d.department_code');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->order_by('v.check_in_time', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    // public function checkoutVisit($visit_id) {
    //     $this->db->where('visit_id', $visit_id);
    //     $this->db->where('check_out_time IS NULL');
        
    //     if ($this->db->update('visits', ['check_out_time' => date('Y-m-d H:i:s')])) {
    //         if ($this->db->affected_rows() > 0) {
    //             return ['success' => true];
    //         }
    //         return ['success' => false, 'error' => 'Already checked out or invalid visit'];
    //     }
        
    //     return ['success' => false, 'error' => $this->db->error()['message']];
    // }

    // public function checkoutVisit($visit_id) {
    //     // Get current server time
    //     $current_time = date('Y-m-d H:i:s');
        
    //     $this->db->where('visit_id', $visit_id);
    //     $this->db->where('check_out_time IS NULL');
        
    //     if ($this->db->update('visits', ['check_out_time' => $current_time])) {
    //         if ($this->db->affected_rows() > 0) {
    //             return ['success' => true];
    //         }
    //         return ['success' => false, 'error' => 'Already checked out or invalid visit'];
    //     }
        
    //     return ['success' => false, 'error' => $this->db->error()['message']];
    // }

    public function checkoutVisit($visit_id) {
        // Ensure we're using the correct timezone
        date_default_timezone_set('Asia/Manila'); // Or your timezone
        
        // Get the current timestamp using MySQL's NOW() function for consistency
        $this->db->where('visit_id', $visit_id);
        $this->db->where('check_out_time IS NULL');
        
        // Use MySQL NOW() to ensure consistency with database timezone
        if ($this->db->set('check_out_time', 'NOW()', FALSE)->update('visits')) {
            if ($this->db->affected_rows() > 0) {
                return ['success' => true];
            }
            return ['success' => false, 'error' => 'Already checked out or invalid visit'];
        }
        
        return ['success' => false, 'error' => $this->db->error()['message']];
    }
    
    // Purpose Management
    public function getAllPurposes() {
        $this->db->select('*');
        $this->db->from('purposes');
        $this->db->order_by('display_order', 'ASC');
        
        $purposes = $this->db->get()->result_array();
        return ['status' => 'success', 'purposes' => $purposes];
    }
    
    // public function addPurpose($data) {
    //     // Check if purpose code exists
    //     $this->db->where('purpose_code', $data['purpose_code']);
    //     $existing = $this->db->get('purposes')->num_rows();
        
    //     if ($existing > 0) {
    //         return ['status' => 'error', 'message' => 'Purpose code already exists'];
    //     }
        
    //     // Get max display order
    //     $this->db->select_max('display_order');
    //     $max_order = $this->db->get('purposes')->row()->display_order;
    //     $display_order = ($max_order ?? 0) + 1;
        
    //     $insert_data = [
    //         'purpose_code' => $data['purpose_code'],
    //         'purpose_name' => $data['purpose_name'],
    //         'icon_class' => $data['icon_class'],
    //         'color_class' => $data['color_class'],
    //         'display_order' => $display_order,
    //         'is_active' => $data['is_active'],
    //         'created_at' => date('Y-m-d H:i:s')
    //     ];
        
    //     if ($this->db->insert('purposes', $insert_data)) {
    //         return ['status' => 'success'];
    //     }
        
    //     return ['status' => 'error', 'message' => $this->db->error()['message']];
    // }

    public function addPurpose($data) {
        // Check if purpose code exists
        $this->db->where('purpose_code', $data['purpose_code']);
        $existing = $this->db->get('purposes')->num_rows();
        
        if ($existing > 0) {
            return ['status' => 'error', 'message' => 'Purpose code already exists'];
        }
        
        // Get max display order
        $this->db->select_max('display_order');
        $max_order = $this->db->get('purposes')->row()->display_order;
        $display_order = ($max_order ?? 0) + 1;
        
        $insert_data = [
            'purpose_code' => $data['purpose_code'],
            'purpose_name' => $data['purpose_name'],
            'name_en' => $data['name_en'] ?? null,
            'name_zh_tw' => $data['name_zh_tw'] ?? null,
            'name_zh_cn' => $data['name_zh_cn'] ?? null,
            'name_fil' => $data['name_fil'] ?? null,
            'name_ja' => $data['name_ja'] ?? null,
            'icon_class' => $data['icon_class'],
            'color_class' => $data['color_class'],
            'display_order' => $display_order,
            'company_owned_by' => $data['company_owned_by'] ?? 'Both',
            'is_active' => $data['is_active'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->db->insert('purposes', $insert_data)) {
            return ['status' => 'success'];
        }
        
        return ['status' => 'error', 'message' => $this->db->error()['message']];
    }
    
    public function togglePurposeStatus($purpose_id, $new_status) {
        $this->db->where('purpose_id', $purpose_id);
        if ($this->db->update('purposes', ['is_active' => $new_status])) {
            return ['status' => 'success', 'new_status' => $new_status];
        }
        
        return ['status' => 'error', 'message' => $this->db->error()['message']];
    }
    
    public function updatePurposeOrder($purpose_id, $direction) {
        // Get current purpose
        $this->db->where('purpose_id', $purpose_id);
        $current = $this->db->get('purposes')->row_array();
        
        if (!$current) {
            return ['status' => 'error', 'message' => 'Purpose not found'];
        }
        
        // Get adjacent purpose
        if ($direction === 'up') {
            $this->db->where('display_order <', $current['display_order']);
            $this->db->order_by('display_order', 'DESC');
        } else {
            $this->db->where('display_order >', $current['display_order']);
            $this->db->order_by('display_order', 'ASC');
        }
        $this->db->limit(1);
        $adjacent = $this->db->get('purposes')->row_array();
        
        if (!$adjacent) {
            return ['status' => 'error', 'message' => 'Cannot move further'];
        }
        
        // Swap display orders
        $this->db->trans_start();
        
        $this->db->where('purpose_id', $current['purpose_id']);
        $this->db->update('purposes', ['display_order' => $adjacent['display_order']]);
        
        $this->db->where('purpose_id', $adjacent['purpose_id']);
        $this->db->update('purposes', ['display_order' => $current['display_order']]);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return ['status' => 'error', 'message' => 'Transaction failed'];
        }
        
        return ['status' => 'success'];
    }
    
    // Emergency Alerts
    public function checkEmergencyAlerts($companyFilter = null) {
        $this->db->select('*');
        $this->db->from('emergency_alerts');
        $this->db->where('acknowledged', 0);
        if ($companyFilter) {
            $this->db->where('company_visited', $companyFilter);
        }
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(10);
        
        $alerts = $this->db->get()->result_array();
        return ['status' => 'success', 'alerts' => $alerts];
    }
    
    public function getLastAlertId($companyFilter = null) {
        $this->db->select_max('alert_id', 'last_id');
        $this->db->from('emergency_alerts');
        if ($companyFilter) {
            $this->db->where('company_visited', $companyFilter);
        }
        
        $result = $this->db->get()->row();
        return ['status' => 'success', 'last_alert_id' => $result->last_id ?? 0];
    }
    
    public function acknowledgeEmergencyAlert($alert_id) {
        $this->db->where('alert_id', $alert_id);
        if ($this->db->update('emergency_alerts', ['acknowledged' => 1])) {
            return ['status' => 'success'];
        }
        
        return ['status' => 'error', 'message' => $this->db->error()['message']];
    }

    // // Auto-checkout expired visits
    // public function autoCheckoutExpiredVisits($companyFilter = null) {
    //     $current_time = date('Y-m-d H:i:s');
        
    //     $this->db->where('check_out_time IS NULL');
    //     $this->db->where('valid_until <=', $current_time);
        
    //     if ($companyFilter) {
    //         $this->db->where('company_visited', $companyFilter);
    //     }
        
    //     // Get the visits that will be auto-checked out
    //     $expired_visits = $this->db->get('visits')->result_array();
        
    //     if (!empty($expired_visits)) {
    //         // Update them to check out at CURRENT TIME (not valid_until)
    //         foreach ($expired_visits as $visit) {
    //             $this->db->where('visit_id', $visit['visit_id']);
    //             $this->db->update('visits', [
    //                 'check_out_time' => $current_time,  // Use current time, not valid_until
    //                 'auto_checkout' => 1  // Track that it was auto-checked out
    //             ]);
    //         }
            
    //         return [
    //             'status' => 'success', 
    //             'checked_out_count' => count($expired_visits),
    //             'visits' => $expired_visits
    //         ];
    //     }
        
    //     return ['status' => 'success', 'checked_out_count' => 0];
    // }
    public function getPurposeById($purpose_id) {
        $this->db->where('purpose_id', $purpose_id);
        $purpose = $this->db->get('purposes')->row_array();
        
        if ($purpose) {
            return ['status' => 'success', 'purpose' => $purpose];
        }
        
        return ['status' => 'error', 'message' => 'Purpose not found'];
    }

    public function canEditPurpose($purpose_id, $companyFilter) {
        $this->db->where('purpose_id', $purpose_id);
        $purpose = $this->db->get('purposes')->row_array();
        
        if (!$purpose) {
            return ['can_edit' => false, 'reason' => 'Purpose not found'];
        }
        
        // Super admin can edit everything
        if ($companyFilter === null) {
            return ['can_edit' => true];
        }
        
        // Both companies can be edited by any admin
        if ($purpose['company_owned_by'] === 'Both') {
            return ['can_edit' => true];
        }
        
        // Check if the purpose belongs to the admin's company
        if ($purpose['company_owned_by'] === $companyFilter) {
            return ['can_edit' => true];
        }
        
        return ['can_edit' => false, 'reason' => 'This purpose belongs to another company'];
    }

    // public function updatePurpose($data) {
    //     $update_data = [
    //         'purpose_name' => $data['purpose_name'],
    //         'icon_class' => $data['icon_class'],
    //         'color_class' => $data['color_class'],
    //         'company_owned_by' => $data['company_owned_by'],
    //         'is_active' => $data['is_active']
    //     ];
        
    //     $this->db->where('purpose_id', $data['purpose_id']);
        
    //     if ($this->db->update('purposes', $update_data)) {
    //         return ['status' => 'success'];
    //     }
        
    //     return ['status' => 'error', 'message' => $this->db->error()['message']];
    // }

    public function updatePurpose($data) {
        $update_data = [
            'purpose_name' => $data['purpose_name'],
            'name_en' => $data['name_en'] ?? null,
            'name_zh_tw' => $data['name_zh_tw'] ?? null,
            'name_zh_cn' => $data['name_zh_cn'] ?? null,
            'name_fil' => $data['name_fil'] ?? null,
            'name_ja' => $data['name_ja'] ?? null,
            'icon_class' => $data['icon_class'],
            'color_class' => $data['color_class'],
            'company_owned_by' => $data['company_owned_by'],
            'is_active' => $data['is_active']
        ];
        
        $this->db->where('purpose_id', $data['purpose_id']);
        
        if ($this->db->update('purposes', $update_data)) {
            return ['status' => 'success'];
        }
        
        return ['status' => 'error', 'message' => $this->db->error()['message']];
    }
    
    // ============================================
    // PART 1: ADD TO Admin_model.php (at the end before closing brace)
    // ============================================

    // Report Methods
    public function getReportTypes() {
        return [
            'department' => [
                'name' => 'Department Report',
                'description' => 'Visitor statistics grouped by department',
                'icon' => 'bi-building',
                'color' => 'primary'
            ],
            'employee_visits' => [
                'name' => 'Employee Visits Report',
                'description' => 'Visit statistics for each employee host',
                'icon' => 'bi-person-badge',
                'color' => 'success'
            ],
            'visitor_visits' => [
                'name' => 'Visitors Report',
                'description' => 'Complete visitor activity and history',
                'icon' => 'bi-people',
                'color' => 'info'
            ],
            'purposes' => [
                'name' => 'Purposes Report',
                'description' => 'Visit breakdown by purpose type',
                'icon' => 'bi-flag',
                'color' => 'warning'
            ],
            'daily' => [
                'name' => 'Daily Report',
                'description' => 'Day-by-day visitor statistics',
                'icon' => 'bi-calendar-day',
                'color' => 'danger'
            ],
            'weekly' => [
                'name' => 'Weekly Report',
                'description' => 'Week-by-week visitor trends',
                'icon' => 'bi-calendar-week',
                'color' => 'secondary'
            ],
            'monthly' => [
                'name' => 'Monthly Report',
                'description' => 'Monthly visitor analytics',
                'icon' => 'bi-calendar-month',
                'color' => 'dark'
            ],
            'annual' => [
                'name' => 'Annual Report',
                'description' => 'Yearly visitor overview',
                'icon' => 'bi-calendar',
                'color' => 'purple'
            ]
        ];
    }

    public function getDepartmentReport($filters = []) {
        $this->db->select('
            d.department_code,
            d.name as department_name,
            COUNT(DISTINCT e.employee_id) as total_employees,
            COUNT(DISTINCT v.visit_id) as total_visits,
            COUNT(DISTINCT v.visitor_id) as unique_visitors,
            AVG(TIMESTAMPDIFF(MINUTE, v.check_in_time, IFNULL(v.check_out_time, NOW()))) as avg_duration_minutes
        ');
        $this->db->from('departments d');
        $this->db->join('employees e', 'd.department_code = e.department_code', 'left');
        $this->db->join('visits v', 'e.employee_id = v.host_employee_id', 'left');
        
        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(v.check_in_time) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(v.check_in_time) <=', $filters['date_to']);
        }
        if (!empty($filters['company_filter'])) {
            $this->db->where('v.company_visited', $filters['company_filter']);
        }
        
        $this->db->group_by('d.department_code');
        $this->db->order_by('total_visits', 'DESC');
        
        $result = $this->db->get()->result_array();
        
        // Calculate totals
        $totals = [
            'total_departments' => count($result),
            'total_employees' => array_sum(array_column($result, 'total_employees')),
            'total_visits' => array_sum(array_column($result, 'total_visits')),
            'unique_visitors' => array_sum(array_column($result, 'unique_visitors'))
        ];
        
        return ['status' => 'success', 'data' => $result, 'totals' => $totals];
    }

    public function getEmployeeVisitsReport($filters = []) {
        $this->db->select('
            e.employee_id,
            e.name as employee_name,
            e.email,
            d.name as department_name,
            e.company_owned_by,
            COUNT(v.visit_id) as total_visits,
            COUNT(DISTINCT v.visitor_id) as unique_visitors,
            MAX(v.check_in_time) as last_visit,
            AVG(TIMESTAMPDIFF(MINUTE, v.check_in_time, IFNULL(v.check_out_time, NOW()))) as avg_duration_minutes
        ');
        $this->db->from('employees e');
        $this->db->join('departments d', 'e.department_code = d.department_code');
        $this->db->join('visits v', 'e.employee_id = v.host_employee_id', 'left');
        
        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(v.check_in_time) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(v.check_in_time) <=', $filters['date_to']);
        }
        if (!empty($filters['company_filter'])) {
            $this->db->group_start();
            $this->db->where('e.company_owned_by', $filters['company_filter']);
            $this->db->or_where('e.company_owned_by', 'Both');
            $this->db->group_end();
        }
        if (!empty($filters['department_code'])) {
            $this->db->where('e.department_code', $filters['department_code']);
        }
        
        $this->db->group_by('e.employee_id');
        $this->db->order_by('total_visits', 'DESC');
        
        $result = $this->db->get()->result_array();
        
        $totals = [
            'total_employees' => count($result),
            'total_visits' => array_sum(array_column($result, 'total_visits')),
            'unique_visitors' => array_sum(array_column($result, 'unique_visitors'))
        ];
        
        return ['status' => 'success', 'data' => $result, 'totals' => $totals];
    }

    public function getVisitorVisitsReport($filters = []) {
        $this->db->select('
            vi.visitor_id,
            vi.first_name,
            vi.last_name,
            vi.email,
            vi.phone,
            vi.company,
            vi.visitor_type,
            COUNT(v.visit_id) as total_visits,
            MIN(v.check_in_time) as first_visit,
            MAX(v.check_in_time) as last_visit,
            AVG(TIMESTAMPDIFF(MINUTE, v.check_in_time, IFNULL(v.check_out_time, NOW()))) as avg_duration_minutes
        ');
        $this->db->from('visitors vi');
        $this->db->join('visits v', 'vi.visitor_id = v.visitor_id', 'left');
        
        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(v.check_in_time) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(v.check_in_time) <=', $filters['date_to']);
        }
        if (!empty($filters['company_filter'])) {
            $this->db->where('v.company_visited', $filters['company_filter']);
        }
        if (!empty($filters['visitor_type'])) {
            $this->db->where('vi.visitor_type', $filters['visitor_type']);
        }
        
        $this->db->group_by('vi.visitor_id');
        $this->db->having('total_visits >', 0);
        $this->db->order_by('total_visits', 'DESC');
        
        $result = $this->db->get()->result_array();
        
        $totals = [
            'total_visitors' => count($result),
            'total_visits' => array_sum(array_column($result, 'total_visits')),
            // 'new_visitors' => count(array_filter($result, fn($r) => $r['visitor_type'] == 'new')),
            // To this:
            'new_visitors' => count(array_filter($result, function($r) { return $r['visitor_type'] == 'new'; })),
            'returning_visitors' => count(array_filter($result, function($r) { return ['visitor_type'] == 'returning'; }))
        ];
        
        return ['status' => 'success', 'data' => $result, 'totals' => $totals];
    }

    public function getPurposesReport($filters = []) {
        $this->db->select('
            p.purpose_code,
            p.purpose_name,
            p.icon_class,
            p.color_class,
            p.company_owned_by,
            COUNT(v.visit_id) as total_visits,
            COUNT(DISTINCT v.visitor_id) as unique_visitors,
            AVG(TIMESTAMPDIFF(MINUTE, v.check_in_time, IFNULL(v.check_out_time, NOW()))) as avg_duration_minutes
        ');
        $this->db->from('purposes p');
        $this->db->join('visits v', 'p.purpose_code = v.purpose', 'left');
        
        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(v.check_in_time) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(v.check_in_time) <=', $filters['date_to']);
        }
        if (!empty($filters['company_filter'])) {
            $this->db->where('v.company_visited', $filters['company_filter']);
        }
        
        $this->db->group_by('p.purpose_code');
        $this->db->order_by('total_visits', 'DESC');
        
        $result = $this->db->get()->result_array();
        
        $totals = [
            'total_purposes' => count($result),
            'total_visits' => array_sum(array_column($result, 'total_visits')),
            'unique_visitors' => array_sum(array_column($result, 'unique_visitors'))
        ];
        
        return ['status' => 'success', 'data' => $result, 'totals' => $totals];
    }

    public function getDailyReport($filters = []) {
        $dateFrom = $filters['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
        $dateTo = $filters['date_to'] ?? date('Y-m-d');
        
        $this->db->select('
            DATE(v.check_in_time) as visit_date,
            DAYNAME(v.check_in_time) as day_name,
            COUNT(v.visit_id) as total_visits,
            COUNT(DISTINCT v.visitor_id) as unique_visitors,
            SUM(CASE WHEN v.check_out_time IS NOT NULL THEN 1 ELSE 0 END) as checked_out,
            SUM(CASE WHEN v.check_out_time IS NULL THEN 1 ELSE 0 END) as still_in,
            AVG(TIMESTAMPDIFF(MINUTE, v.check_in_time, IFNULL(v.check_out_time, NOW()))) as avg_duration_minutes
        ');
        $this->db->from('visits v');
        $this->db->where('DATE(v.check_in_time) >=', $dateFrom);
        $this->db->where('DATE(v.check_in_time) <=', $dateTo);
        
        if (!empty($filters['company_filter'])) {
            $this->db->where('v.company_visited', $filters['company_filter']);
        }
        
        $this->db->group_by('DATE(v.check_in_time)');
        $this->db->order_by('visit_date', 'ASC');
        
        $result = $this->db->get()->result_array();
        
        $totals = [
            'total_days' => count($result),
            'total_visits' => array_sum(array_column($result, 'total_visits')),
            'avg_daily_visits' => count($result) > 0 ? round(array_sum(array_column($result, 'total_visits')) / count($result), 1) : 0,
            'busiest_day' => !empty($result) ? max(array_column($result, 'total_visits')) : 0
        ];
        
        return ['status' => 'success', 'data' => $result, 'totals' => $totals];
    }

    public function getWeeklyReport($filters = []) {
        $dateFrom = $filters['date_from'] ?? date('Y-m-d', strtotime('-12 weeks'));
        $dateTo = $filters['date_to'] ?? date('Y-m-d');
        
        $this->db->select('
            YEARWEEK(v.check_in_time, 1) as year_week,
            MIN(DATE(v.check_in_time)) as week_start,
            MAX(DATE(v.check_in_time)) as week_end,
            COUNT(v.visit_id) as total_visits,
            COUNT(DISTINCT v.visitor_id) as unique_visitors,
            AVG(TIMESTAMPDIFF(MINUTE, v.check_in_time, IFNULL(v.check_out_time, NOW()))) as avg_duration_minutes
        ');
        $this->db->from('visits v');
        $this->db->where('DATE(v.check_in_time) >=', $dateFrom);
        $this->db->where('DATE(v.check_in_time) <=', $dateTo);
        
        if (!empty($filters['company_filter'])) {
            $this->db->where('v.company_visited', $filters['company_filter']);
        }
        
        $this->db->group_by('YEARWEEK(v.check_in_time, 1)');
        $this->db->order_by('year_week', 'ASC');
        
        $result = $this->db->get()->result_array();
        
        $totals = [
            'total_weeks' => count($result),
            'total_visits' => array_sum(array_column($result, 'total_visits')),
            'avg_weekly_visits' => count($result) > 0 ? round(array_sum(array_column($result, 'total_visits')) / count($result), 1) : 0
        ];
        
        return ['status' => 'success', 'data' => $result, 'totals' => $totals];
    }

    public function getMonthlyReport($filters = []) {
        $dateFrom = $filters['date_from'] ?? date('Y-m-d', strtotime('-12 months'));
        $dateTo = $filters['date_to'] ?? date('Y-m-d');
        
        $this->db->select('
            DATE_FORMAT(v.check_in_time, "%Y-%m") as year_month,
            MONTHNAME(v.check_in_time) as month_name,
            YEAR(v.check_in_time) as year,
            COUNT(v.visit_id) as total_visits,
            COUNT(DISTINCT v.visitor_id) as unique_visitors,
            AVG(TIMESTAMPDIFF(MINUTE, v.check_in_time, IFNULL(v.check_out_time, NOW()))) as avg_duration_minutes
        ');
        $this->db->from('visits v');
        $this->db->where('DATE(v.check_in_time) >=', $dateFrom);
        $this->db->where('DATE(v.check_in_time) <=', $dateTo);
        
        if (!empty($filters['company_filter'])) {
            $this->db->where('v.company_visited', $filters['company_filter']);
        }
        
        $this->db->group_by('DATE_FORMAT(v.check_in_time, "%Y-%m")');
        $this->db->order_by('year_month', 'ASC');
        
        $result = $this->db->get()->result_array();
        
        $totals = [
            'total_months' => count($result),
            'total_visits' => array_sum(array_column($result, 'total_visits')),
            'avg_monthly_visits' => count($result) > 0 ? round(array_sum(array_column($result, 'total_visits')) / count($result), 1) : 0
        ];
        
        return ['status' => 'success', 'data' => $result, 'totals' => $totals];
    }

    public function getAnnualReport($filters = []) {
        $this->db->select('
            YEAR(v.check_in_time) as year,
            COUNT(v.visit_id) as total_visits,
            COUNT(DISTINCT v.visitor_id) as unique_visitors,
            COUNT(DISTINCT DATE(v.check_in_time)) as active_days,
            AVG(TIMESTAMPDIFF(MINUTE, v.check_in_time, IFNULL(v.check_out_time, NOW()))) as avg_duration_minutes
        ');
        $this->db->from('visits v');
        
        if (!empty($filters['company_filter'])) {
            $this->db->where('v.company_visited', $filters['company_filter']);
        }
        
        $this->db->group_by('YEAR(v.check_in_time)');
        $this->db->order_by('year', 'ASC');
        
        $result = $this->db->get()->result_array();
        
        $totals = [
            'total_years' => count($result),
            'total_visits' => array_sum(array_column($result, 'total_visits')),
            'avg_yearly_visits' => count($result) > 0 ? round(array_sum(array_column($result, 'total_visits')) / count($result), 1) : 0
        ];
        
        return ['status' => 'success', 'data' => $result, 'totals' => $totals];
    }

    public function getCompanyComparisonReport($filters = []) {
        $this->db->select('
            v.company_visited,
            COUNT(v.visit_id) as total_visits,
            COUNT(DISTINCT v.visitor_id) as unique_visitors,
            COUNT(DISTINCT DATE(v.check_in_time)) as active_days,
            AVG(TIMESTAMPDIFF(MINUTE, v.check_in_time, IFNULL(v.check_out_time, NOW()))) as avg_duration_minutes
        ');
        $this->db->from('visits v');
        
        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(v.check_in_time) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(v.check_in_time) <=', $filters['date_to']);
        }
        
        $this->db->group_by('v.company_visited');
        $this->db->order_by('total_visits', 'DESC');
        
        return $this->db->get()->result_array();
    }

    // ============================================
    // ADD TO Admin_model.php (add this method)
    // ============================================

    /**
     * Bulk checkout multiple visits at once
     * @param array $visit_ids Array of visit IDs to checkout
     * @return array Result with success count and any errors
     */
    public function bulkCheckoutVisits($visit_ids) {
        if (empty($visit_ids) || !is_array($visit_ids)) {
            return [
                'success' => false, 
                'error' => 'No visits selected'
            ];
        }
        
        // Ensure timezone consistency
        date_default_timezone_set('Asia/Manila'); // Or your timezone
        
        $success_count = 0;
        $failed_count = 0;
        $failed_ids = [];
        
        $this->db->trans_start();
        
        foreach ($visit_ids as $visit_id) {
            $visit_id = (int) $visit_id;
            
            // Check if visit exists and is not already checked out
            $this->db->where('visit_id', $visit_id);
            $this->db->where('check_out_time IS NULL');
            $visit = $this->db->get('visits')->row();
            
            if ($visit) {
                $this->db->where('visit_id', $visit_id);
                $this->db->set('check_out_time', 'NOW()', FALSE);
                
                if ($this->db->update('visits')) {
                    $success_count++;
                } else {
                    $failed_count++;
                    $failed_ids[] = $visit_id;
                }
            } else {
                $failed_count++;
                $failed_ids[] = $visit_id;
            }
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return [
                'success' => false,
                'error' => 'Transaction failed',
                'success_count' => 0,
                'failed_count' => count($visit_ids)
            ];
        }
        
        return [
            'success' => true,
            'success_count' => $success_count,
            'failed_count' => $failed_count,
            'failed_ids' => $failed_ids,
            'message' => "{$success_count} visitor(s) checked out successfully" . 
                        ($failed_count > 0 ? ", {$failed_count} failed" : "")
        ];
    }

    /**
     * Checkout all active visits (optional: with company filter)
     * @param string|null $companyFilter Company to filter by
     * @return array Result with success count
     */
    public function checkoutAllActiveVisits($companyFilter = null) {
        date_default_timezone_set('Asia/Manila');
        
        // Get all active visit IDs
        $this->db->select('visit_id');
        $this->db->from('visits');
        $this->db->where('check_out_time IS NULL');
        
        if ($companyFilter) {
            $this->db->where('company_visited', $companyFilter);
        }
        
        $active_visits = $this->db->get()->result_array();
        
        if (empty($active_visits)) {
            return [
                'success' => true,
                'success_count' => 0,
                'message' => 'No active visits to check out'
            ];
        }
        
        $visit_ids = array_column($active_visits, 'visit_id');
        
        return $this->bulkCheckoutVisits($visit_ids);
    }
}