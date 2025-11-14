<?php

// File: models/Department.php
class Department {
    private $conn;
    private $table_name = "departments";

    public $department_code;
    public $name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create department
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    department_code = :department_code,
                    name = :name";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":department_code", $this->department_code);
        $stmt->bindParam(":name", $this->name);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all departments
    public function read() {
        $query = "SELECT 
                    d.*,
                    COUNT(DISTINCT e.employee_id) as employee_count,
                    COUNT(DISTINCT v.visit_id) as total_visits
                FROM " . $this->table_name . " d
                LEFT JOIN employees e ON d.department_code = e.department_code
                LEFT JOIN visits v ON e.employee_id = v.host_employee_id
                GROUP BY d.department_code
                ORDER BY d.name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update department
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET name = :name
                WHERE department_code = :department_code";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":department_code", $this->department_code);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete department
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE department_code = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->department_code);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
