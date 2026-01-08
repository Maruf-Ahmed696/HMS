<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Room.php';
require_once __DIR__ . '/AuthController.php';

AuthController::requireLogin();

class BookingController {
    private $bookingModel;
    private $roomModel;
    
    public function __construct() {
        $this->bookingModel = new Booking();
        $this->roomModel = new Room();
    }
    
    // Create booking
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $guest_name = $_POST['guest_name'] ?? '';
            $guest_email = $_POST['guest_email'] ?? '';
            $guest_phone = $_POST['guest_phone'] ?? '';
            $guest_address = $_POST['guest_address'] ?? '';
            $guest_id_number = $_POST['guest_id_number'] ?? '';
            $room_id = $_POST['room_id'] ?? 0;
            $check_in = $_POST['check_in'] ?? '';
            $check_out = $_POST['check_out'] ?? '';
            
            // Validation
            if (empty($guest_name) || empty($guest_email) || empty($guest_phone) || 
                $room_id <= 0 || empty($check_in) || empty($check_out)) {
                $_SESSION['error'] = "Please fill in all required fields";
                $redirectTo = (isset($_POST['from_user']) && $_POST['from_user'] == '1') ? '../../book.php' : '../views/bookings.php';
                header('Location: ' . $redirectTo);
                exit();
            }
            
            // Validate dates
            $check_in_date = strtotime($check_in);
            $check_out_date = strtotime($check_out);
            
            if ($check_in_date >= $check_out_date) {
                $_SESSION['error'] = "Check-out date must be after check-in date";
                $redirectTo = (isset($_POST['from_user']) && $_POST['from_user'] == '1') ? '../../book.php' : '../views/bookings.php';
                header('Location: ' . $redirectTo);
                exit();
            }

            // Validate email
            if (!filter_var($guest_email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email address";
                $redirectTo = (isset($_POST['from_user']) && $_POST['from_user'] == '1') ? '../../book.php' : '../views/bookings.php';
                header('Location: ' . $redirectTo);
                exit();
            }
            
            // Calculate total amount
            $room = $this->roomModel->getById($room_id);
            if (!$room) {
                $_SESSION['error'] = "Selected room not found";
                $redirectTo = (isset($_POST['from_user']) && $_POST['from_user'] == '1') ? '../../book.php' : '../views/bookings.php';
                header('Location: ' . $redirectTo);
                exit();
            }
            $days = ceil(($check_out_date - $check_in_date) / (60 * 60 * 24));
            $total_amount = $room['price'] * $days;
            
            $result = $this->bookingModel->create(
                $guest_name, $guest_email, $guest_phone, $guest_address, $guest_id_number,
                $room_id, $check_in, $check_out, $total_amount
            );
            
            if ($result) {
                $_SESSION['success'] = "Booking created successfully";
            } else {
                $_SESSION['error'] = "Failed to create booking";
            }
            
            header('Location: ../views/bookings.php');
            exit();
        }
    }
    
    // Update booking status
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            if ($id <= 0 || empty($status)) {
                $_SESSION['error'] = "Invalid data provided";
                header('Location: ../views/bookings.php');
                exit();
            }
            
            $result = $this->bookingModel->updateStatus($id, $status);
            
            if ($result) {
                $_SESSION['success'] = "Booking status updated successfully";
            } else {
                $_SESSION['error'] = "Failed to update booking status";
            }
            
            $redirectTo = (isset($_POST['from_user']) && $_POST['from_user'] == '1') ? '../../my_bookings.php' : '../views/bookings.php';
            header('Location: ' . $redirectTo);
            exit();
        }
    }
    
    // Delete booking
    public function delete() {
        AuthController::requireAdmin();
        
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            $result = $this->bookingModel->delete($id);
            
            if ($result) {
                $_SESSION['success'] = "Booking deleted successfully";
            } else {
                $_SESSION['error'] = "Failed to delete booking";
            }
        }
        
        header('Location: ../views/bookings.php');
        exit();
    }
    
    // Get booking data as JSON (for AJAX)
    public function getBooking() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $booking = $this->bookingModel->getById($id);
            
            header('Content-Type: application/json');
            echo json_encode($booking);
            exit();
        }
    }
    
    // Calculate price (AJAX)
    public function calculatePrice() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $room_id = $_POST['room_id'] ?? 0;
            $check_in = $_POST['check_in'] ?? '';
            $check_out = $_POST['check_out'] ?? '';
            
            $room = $this->roomModel->getById($room_id);
            $check_in_date = strtotime($check_in);
            $check_out_date = strtotime($check_out);
            
            $days = ceil(($check_out_date - $check_in_date) / (60 * 60 * 24));
            $total = $room['price'] * $days;
            
            header('Content-Type: application/json');
            echo json_encode([
                'days' => $days,
                'price_per_day' => $room['price'],
                'total' => $total
            ]);
            exit();
        }
    }
}

// Handle actions
if (isset($_GET['action'])) {
    $controller = new BookingController();
    
    switch ($_GET['action']) {
        case 'create':
            $controller->create();
            break;
        case 'update_status':
            $controller->updateStatus();
            break;
        case 'delete':
            $controller->delete();
            break;
        case 'get':
            $controller->getBooking();
            break;
        case 'calculate':
            $controller->calculatePrice();
            break;
    }
}
?>