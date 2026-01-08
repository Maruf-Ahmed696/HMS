<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../admin/controllers/AuthController.php';
require_once __DIR__ . '/../../admin/models/Room.php';

class GuestController {
    public static function home() {
        include __DIR__ . '/../views/home.php';
    }

    public static function book() {
        // require login to book
        AuthController::requireLogin();

        $roomModel = new Room();
        $availableRooms = $roomModel->getAvailable();

        include __DIR__ . '/../views/book.php';
    }

    public static function rooms() {
        $roomModel = new Room();
        $rooms = $roomModel->getAll();
        include __DIR__ . '/../views/rooms.php';
    }
}
?>