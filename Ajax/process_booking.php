<?php
require_once '../Controllers/BookingController.php';

$controller = new BookingController();
$response = $controller->handleBooking($_POST);

header('Content-Type: application/json');
echo json_encode($response);
?>