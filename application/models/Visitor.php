<?php
// // File: config/database.php
// class Database {
//     private $host = "localhost";
//     private $db_name = "vms";
//     private $username = "root";
//     private $password = "";
//     public $conn;

//     public function getConnection() {
//         $this->conn = null;
//         try {
//             $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
//             $this->conn->exec("set names utf8mb4");
//             $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         } catch(PDOException $exception) {
//             echo "Connection error: " . $exception->getMessage();
//         }
//         return $this->conn;
//     }
// }

// File: models/Visitor.php
class Visitor {
    private $conn;
    private $table_name = "visitors";

    public $visitor_id;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $company;
    public $photo;
    public $visitor_type;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create visitor
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    first_name = :first_name,
                    last_name = :last_name,
                    email = :email,
                    phone = :phone,
                    company = :company,
                    photo = :photo,
                    visitor_type = :visitor_type";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->company = htmlspecialchars(strip_tags($this->company));
        $this->visitor_type = htmlspecialchars(strip_tags($this->visitor_type));

        // Bind values
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":company", $this->company);
        $stmt->bindParam(":photo", $this->photo);
        $stmt->bindParam(":visitor_type", $this->visitor_type);

        if($stmt->execute()) {
            $this->visitor_id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Read all visitors
    public function read() {
        $query = "SELECT 
                    v.*,
                    COUNT(DISTINCT visits.visit_id) as total_visits,
                    MAX(visits.check_in_time) as last_visit
                FROM " . $this->table_name . " v
                LEFT JOIN visits ON v.visitor_id = visits.visitor_id
                GROUP BY v.visitor_id
                ORDER BY v.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single visitor
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE visitor_id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->visitor_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->company = $row['company'];
            $this->photo = $row['photo'];
            $this->visitor_type = $row['visitor_type'];
            return true;
        }
        return false;
    }

    // Update visitor
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    first_name = :first_name,
                    last_name = :last_name,
                    email = :email,
                    phone = :phone,
                    company = :company,
                    visitor_type = :visitor_type
                WHERE visitor_id = :visitor_id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->company = htmlspecialchars(strip_tags($this->company));
        $this->visitor_type = htmlspecialchars(strip_tags($this->visitor_type));
        $this->visitor_id = htmlspecialchars(strip_tags($this->visitor_id));

        // Bind values
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":company", $this->company);
        $stmt->bindParam(":visitor_type", $this->visitor_type);
        $stmt->bindParam(":visitor_id", $this->visitor_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete visitor
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE visitor_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $this->visitor_id = htmlspecialchars(strip_tags($this->visitor_id));
        $stmt->bindParam(1, $this->visitor_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Search visitors
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . "
                WHERE first_name LIKE ? 
                OR last_name LIKE ? 
                OR email LIKE ?
                OR company LIKE ?
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $keywords);
        
        $stmt->execute();
        return $stmt;
    }
}
