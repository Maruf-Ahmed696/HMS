<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/Room.php';
require_once __DIR__ . '/AuthController.php';

AuthController::requireLogin();

class RoomController {
    private $roomModel;
    
    public function __construct() {
        $this->roomModel = new Room();
    }
    
    // Create room
    public function create() {
        AuthController::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $room_number = $_POST['room_number'] ?? '';
            $room_type = $_POST['room_type'] ?? '';
            $price = $_POST['price'] ?? 0;
            $status = $_POST['status'] ?? 'available';
            $description = $_POST['description'] ?? '';
            
            if (empty($room_number) || empty($room_type) || $price <= 0) {
                $_SESSION['error'] = "Please fill in all required fields";
                header('Location: ../views/rooms.php');
                exit();
            }
            
            $result = $this->roomModel->create($room_number, $room_type, $price, $status, $description);
            
            if ($result) {
                $_SESSION['success'] = "Room created successfully";
            } else {
                $_SESSION['error'] = "Failed to create room";
            }
            
            header('Location: ../views/rooms.php');
            exit();
        }
    }
    
    // Update room
    public function update() {
        AuthController::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $room_number = $_POST['room_number'] ?? '';
            $room_type = $_POST['room_type'] ?? '';
            $price = $_POST['price'] ?? 0;
            $status = $_POST['status'] ?? 'available';
            $description = $_POST['description'] ?? '';
            
            if ($id <= 0 || empty($room_number) || empty($room_type) || $price <= 0) {
                $_SESSION['error'] = "Invalid data provided";
                header('Location: ../views/rooms.php');
                exit();
            }
            
            $result = $this->roomModel->update($id, $room_number, $room_type, $price, $status, $description);
            
            if ($result) {
                $_SESSION['success'] = "Room updated successfully";
            } else {
                $_SESSION['error'] = "Failed to update room";
            }
            
            header('Location: ../views/rooms.php');
            exit();
        }
    }
    
    // Delete room
    public function delete() {
        AuthController::requireAdmin();
        
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            $result = $this->roomModel->delete($id);
            
            if ($result) {
                $_SESSION['success'] = "Room deleted successfully";
            } else {
                $_SESSION['error'] = "Failed to delete room";
            }
        }
        
        header('Location: ../views/rooms.php');
        exit();
    }
    
    // Get room data as JSON (for AJAX)
    public function getRoom() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $room = $this->roomModel->getById($id);
            
            header('Content-Type: application/json');
            echo json_encode($room);
            exit();
        }
    }
}

// Handle actions
if (isset($_GET['action'])) {
    $controller = new RoomController();
    
    switch ($_GET['action']) {
        case 'create':
            $controller->create();
            break;
        case 'update':
            $controller->update();
            break;
        case 'delete':
            $controller->delete();
            break;
        case 'get':
            $controller->getRoom();
            break;
    }
}
?>