<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // Sanitize input
    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    // Login user
    public function login($username, $password) {
        $username = $this->sanitize($username);
        
        $stmt = $this->conn->prepare("SELECT id, username, password, email, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        
        return false;
    }
    
    // Create new user
    public function create($username, $password, $email, $role = 'user') {
        $username = $this->sanitize($username);
        $email = $this->sanitize($email);
        $role = $this->sanitize($role);
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashed_password, $email, $role);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    // Get user by ID
    public function getById($id) {
        $id = (int)$id;
        
        $stmt = $this->conn->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    // Get all users
    public function getAll() {
        $query = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        return $users;
    }
    
    // Update user (supports optional password change)
    public function update($id, $username, $email, $role, $password = null) {
        $id = (int)$id;
        $username = $this->sanitize($username);
        $email = $this->sanitize($email);
        $role = $this->sanitize($role);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ?, role = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $username, $email, $role, $hashed_password, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $username, $email, $role, $id);
        }
        
        return $stmt->execute();
    }
    
    // Delete user
    public function delete($id) {
        $id = (int)$id;
        
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
}
?>