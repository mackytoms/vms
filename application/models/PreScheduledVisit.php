<?php


// File: models/PreScheduledVisit.php
class PreScheduledVisit {
    private $conn;
    private $table_name = "pre_scheduled_visits";

    public $booking_id;
    public $booking_code;
    public $visitor_name;
    public $visitor_email;
    public $visitor_company;
    public $host_employee_id;
    public $scheduled_time;
    public $purpose;
    public $status;
    public $visit_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create scheduled visit
    public function create() {
        // Generate booking code
        $this->booking_code = $this->generateBookingCode();
        
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    booking_code = :booking_code,
                    visitor_name = :visitor_name,
                    visitor_email = :visitor_email,
                    visitor_company = :visitor_company,
                    host_employee_id = :host_employee_id,
                    scheduled_time = :scheduled_time,
                    purpose = :purpose,
                    status = 'scheduled'";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(":booking_code", $this->booking_code);
        $stmt->bindParam(":visitor_name", $this->visitor_name);
        $stmt->bindParam(":visitor_email", $this->visitor_email);
        $stmt->bindParam(":visitor_company", $this->visitor_company);
        $stmt->bindParam(":host_employee_id", $this->host_employee_id);
        $stmt->bindParam(":scheduled_time", $this->scheduled_time);
        $stmt->bindParam(":purpose", $this->purpose);

        if($stmt->execute()) {
            $this->booking_id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Generate unique booking code
    private function generateBookingCode() {
        $prefix = "BOOK";
        $year = date('Y');
        $random = sprintf('%04d', mt_rand(1, 9999));
        return $prefix . '-' . $year . '-' . $random;
    }

    // Read all scheduled visits
    public function read() {
        $query = "SELECT 
                    ps.*,
                    e.name as host_name,
                    d.name as department_name
                FROM " . $this->table_name . " ps
                JOIN employees e ON ps.host_employee_id = e.employee_id
                JOIN departments d ON e.department_code = d.department_code
                ORDER BY ps.scheduled_time ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update status
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . "
                SET status = :status
                WHERE booking_id = :booking_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":booking_id", $this->booking_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Check in scheduled visitor
    public function checkIn($visitor_id, $visit_id) {
        $query = "UPDATE " . $this->table_name . "
                SET 
                    status = 'checked_in',
                    visit_id = :visit_id
                WHERE booking_id = :booking_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":visit_id", $visit_id);
        $stmt->bindParam(":booking_id", $this->booking_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get today's scheduled visits
    public function getTodayScheduled() {
        $query = "SELECT 
                    ps.*,
                    e.name as host_name
                FROM " . $this->table_name . " ps
                JOIN employees e ON ps.host_employee_id = e.employee_id
                WHERE DATE(ps.scheduled_time) = CURDATE()
                AND ps.status = 'scheduled'
                ORDER BY ps.scheduled_time ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
