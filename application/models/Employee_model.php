<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// class Employee_model extends CI_Model {
    
//     public function __construct() {
//         parent::__construct();
//     }
    
//     public function get_all_active_employees($company_visited) {
//         $this->db->select('
//             e.employee_id, 
//             e.name, 
//             e.email, 
//             e.department_code,
//             COALESCE(d.name, e.department_code) as department_name
//         ');
//         $this->db->from('employees e');
//         $this->db->join('departments d', 'e.department_code = d.department_code', 'left');
//         $this->db->where('e.company_visited', $company_visited);
//         $this->db->where('e.status', 'active');
//         $this->db->order_by('e.name', 'ASC');
        
//         $query = $this->db->get();
        
//         if ($query === FALSE) {
//             // Database error occurred
//             log_message('error', 'Database error in get_all_active_employees: ' . $this->db->error()['message']);
//             return [];
//         }
        
//         return $query->result_array();
//     }
// }