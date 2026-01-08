<?php
require_once 'db_connect.php';

function insertBooking($name, $contact, $room, $days, $price) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO bookings (customer_name, contact, room_type, days, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $name, $contact, $room, $days, $price);
    return $stmt->execute();
}
?>