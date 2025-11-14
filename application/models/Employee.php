<?php


// File: models/Employee.php
class Employee {
    private $conn;
    private $table_name = "employees";

    public $employee_id;
    public $name;
    public $email;
    public $department_code;
    public $is_active;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create employee
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    employee_id = :employee_id,
                    name = :name,
                    email = :email,
                    department_code = :department_code,
                    is_active = :is_active";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind
        $stmt->bindParam(":employee_id", $this->employee_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":department_code", $this->department_code);
        $stmt->bindParam(":is_active", $this->is_active);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all employees
    public function read() {
        $query = "SELECT 
                    e.*,
                    d.name as department_name,
                    COUNT(DISTINCT v.visit_id) as total_visits_hosted
                FROM " . $this->table_name . " e
                LEFT JOIN departments d ON e.department_code = d.department_code
                LEFT JOIN visits v ON e.employee_id = v.host_employee_id
                GROUP BY e.employee_id
                ORDER BY e.name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single employee
    public function readOne() {
        $query = "SELECT 
                    e.*,
                    d.name as department_name
                FROM " . $this->table_name . " e
                LEFT JOIN departments d ON e.department_code = d.department_code
                WHERE e.employee_id = ?
                LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->employee_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->department_code = $row['department_code'];
            $this->is_active = $row['is_active'];
            return true;
        }
        return false;
    }

    // Update employee
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    name = :name,
                    email = :email,
                    department_code = :department_code,
                    is_active = :is_active
                WHERE employee_id = :employee_id";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":department_code", $this->department_code);
        $stmt->bindParam(":is_active", $this->is_active);
        $stmt->bindParam(":employee_id", $this->employee_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete employee
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE employee_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->employee_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get active employees for dropdown
    public function getActiveEmployees() {
        $query = "SELECT employee_id, name, department_code 
                FROM " . $this->table_name . "
                WHERE is_active = 1
                ORDER BY name ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
