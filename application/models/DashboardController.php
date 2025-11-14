<?php

// File: controllers/DashboardController.php
class DashboardController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDashboardStats() {
        $stats = array();

        // Today's visitors
        $query = "SELECT COUNT(*) as count FROM visits 
                WHERE DATE(check_in_time) = CURDATE()";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['today_visitors'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Currently in building
        $query = "SELECT COUNT(*) as count FROM visits 
                WHERE check_out_time IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['currently_in'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Scheduled today
        $query = "SELECT COUNT(*) as count FROM pre_scheduled_visits 
                WHERE DATE(scheduled_time) = CURDATE() 
                AND status = 'scheduled'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['scheduled_today'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Average duration
        $query = "SELECT AVG(TIMESTAMPDIFF(MINUTE, check_in_time, check_out_time)) as avg_duration 
                FROM visits 
                WHERE check_out_time IS NOT NULL 
                AND DATE(check_in_time) = CURDATE()";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $avg_minutes = $stmt->fetch(PDO::FETCH_ASSOC)['avg_duration'];
        $stats['avg_duration'] = round($avg_minutes / 60, 1) . 'h';

        // Total departments
        $query = "SELECT COUNT(*) as count FROM departments";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['total_departments'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Total employees
        $query = "SELECT COUNT(*) as count FROM employees WHERE is_active = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['total_employees'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Total visitors
        $query = "SELECT COUNT(*) as count FROM visitors";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['total_visitors'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Purpose breakdown
        $query = "SELECT purpose, COUNT(*) as count 
                FROM visits 
                WHERE DATE(check_in_time) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY purpose";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['purpose_breakdown'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    public function getRecentActivity($limit = 10) {
        $query = "SELECT 
                    v.*,
                    CONCAT(vi.first_name, ' ', vi.last_name) as visitor_name,
                    vi.company as visitor_company,
                    e.name as host_name,
                    d.name as department_name
                FROM visits v
                JOIN visitors vi ON v.visitor_id = vi.visitor_id
                JOIN employees e ON v.host_employee_id = e.employee_id
                JOIN departments d ON e.department_code = d.department_code
                ORDER BY v.check_in_time DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}