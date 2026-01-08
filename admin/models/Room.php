<?php
require_once __DIR__ . '/../../config/database.php';

class Room {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    

    public function create($room_number, $room_type, $price, $status, $description) {
        $room_number = $this->sanitize($room_number);
        $room_type = $this->sanitize($room_type);
        $price = (float)$price;
        $status = $this->sanitize($status);
        $description = $this->sanitize($description);
        
        $stmt = $this->conn->prepare("INSERT INTO rooms (room_number, room_type, price, status, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $room_number, $room_type, $price, $status, $description);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    public function getAll() {
        $query = "SELECT * FROM rooms ORDER BY room_number ASC";
        $result = $this->conn->query($query);
        
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        
        return $rooms;
    }
    
    public function getAvailable() {
        $query = "SELECT * FROM rooms WHERE status = 'available' ORDER BY room_number ASC";
        $result = $this->conn->query($query);
        
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        
        return $rooms;
    }
    public function getById($id) {
        $id = (int)$id;
        
        $stmt = $this->conn->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    public function update($id, $room_number, $room_type, $price, $status, $description) {
        $id = (int)$id;
        $room_number = $this->sanitize($room_number);
        $room_type = $this->sanitize($room_type);
        $price = (float)$price;
        $status = $this->sanitize($status);
        $description = $this->sanitize($description);
        
        $stmt = $this->conn->prepare("UPDATE rooms SET room_number = ?, room_type = ?, price = ?, status = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssdssi", $room_number, $room_type, $price, $status, $description, $id);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $id = (int)$id;
        
        $stmt = $this->conn->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    public function updateStatus($id, $status) {
        $id = (int)$id;
        $status = $this->sanitize($status);
        
        $stmt = $this->conn->prepare("UPDATE rooms SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        
        return $stmt->execute();
    }
}
?>