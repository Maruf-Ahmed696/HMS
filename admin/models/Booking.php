<?php
require_once __DIR__ . '/../../config/database.php';

class Booking {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    // Create booking with guest info
    public function create($guest_name, $guest_email, $guest_phone, $guest_address, $guest_id_number, 
                          $room_id, $check_in, $check_out, $total_amount) {
        
        // Start transaction
        $this->conn->begin_transaction();
        
        try {
            // Sanitize inputs
            $guest_name = $this->sanitize($guest_name);
            $guest_email = $this->sanitize($guest_email);
            $guest_phone = $this->sanitize($guest_phone);
            $guest_address = $this->sanitize($guest_address);
            $guest_id_number = $this->sanitize($guest_id_number);
            $room_id = (int)$room_id;
            $check_in = $this->sanitize($check_in);
            $check_out = $this->sanitize($check_out);
            $total_amount = (float)$total_amount;
            
            // Insert guest
            $stmt = $this->conn->prepare("INSERT INTO guests (name, email, phone, address, id_number) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $guest_name, $guest_email, $guest_phone, $guest_address, $guest_id_number);
            $stmt->execute();
            $guest_id = $this->conn->insert_id;
            
            // Insert booking
            $stmt = $this->conn->prepare("INSERT INTO bookings (guest_id, room_id, check_in, check_out, total_amount, status) VALUES (?, ?, ?, ?, ?, 'confirmed')");
            $stmt->bind_param("iissd", $guest_id, $room_id, $check_in, $check_out, $total_amount);
            $stmt->execute();
            $booking_id = $this->conn->insert_id;
            
            // Update room status
            $stmt = $this->conn->prepare("UPDATE rooms SET status = 'booked' WHERE id = ?");
            $stmt->bind_param("i", $room_id);
            $stmt->execute();
            
            $this->conn->commit();
            return $booking_id;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    
    // Get all bookings with guest and room info
    public function getAll() {
        $query = "SELECT b.*, g.name as guest_name, g.email as guest_email, g.phone as guest_phone, 
                         r.room_number, r.room_type, r.price
                  FROM bookings b
                  JOIN guests g ON b.guest_id = g.id
                  JOIN rooms r ON b.room_id = r.id
                  ORDER BY b.created_at DESC";
        
        $result = $this->conn->query($query);
        
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        
        return $bookings;
    }
    
    // Get booking by ID
    public function getById($id) {
        $id = (int)$id;
        
        $stmt = $this->conn->prepare("SELECT b.*, g.name as guest_name, g.email as guest_email, g.phone as guest_phone,
                                             g.address as guest_address, g.id_number as guest_id_number,
                                             r.room_number, r.room_type, r.price
                                      FROM bookings b
                                      JOIN guests g ON b.guest_id = g.id
                                      JOIN rooms r ON b.room_id = r.id
                                      WHERE b.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    // Get bookings by guest email
    public function getByGuestEmail($email) {
        $email = $this->sanitize($email);

        $stmt = $this->conn->prepare("SELECT b.*, g.name as guest_name, g.email as guest_email, g.phone as guest_phone, r.room_number, r.room_type, r.price FROM bookings b JOIN guests g ON b.guest_id = g.id JOIN rooms r ON b.room_id = r.id WHERE g.email = ? ORDER BY b.created_at DESC");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        return $bookings;
    }
    
    // Update booking status
    public function updateStatus($id, $status) {
        $id = (int)$id;
        $status = $this->sanitize($status);
        
        $this->conn->begin_transaction();
        
        try {
            // Get booking info
            $booking = $this->getById($id);
            
            $stmt = $this->conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            $stmt->execute();
            
            // If cancelled or completed, make room available
            if ($status == 'cancelled' || $status == 'completed') {
                $stmt = $this->conn->prepare("UPDATE rooms SET status = 'available' WHERE id = ?");
                $stmt->bind_param("i", $booking['room_id']);
                $stmt->execute();
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    
    // Delete booking
    public function delete($id) {
        $id = (int)$id;
        
        $stmt = $this->conn->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    // Get statistics
    public function getStats() {
        $stats = [];
        
        $result = $this->conn->query("SELECT COUNT(*) as total FROM bookings");
        $stats['total_bookings'] = $result->fetch_assoc()['total'];
        
        $result = $this->conn->query("SELECT COUNT(*) as total FROM bookings WHERE status = 'confirmed'");
        $stats['confirmed_bookings'] = $result->fetch_assoc()['total'];
        
        $result = $this->conn->query("SELECT COUNT(*) as total FROM rooms WHERE status = 'available'");
        $stats['available_rooms'] = $result->fetch_assoc()['total'];
        
        $result = $this->conn->query("SELECT SUM(total_amount) as revenue FROM bookings WHERE status != 'cancelled'");
        $stats['total_revenue'] = $result->fetch_assoc()['revenue'] ?? 0;
        
        return $stats;
    }
}
?>