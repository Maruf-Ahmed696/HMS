<?php
require_once 'db_connect.php';

function insertRoom($type, $number, $availability, $status)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO rooms (room_type, room_number, availability, room_status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $type, $number, $availability, $status);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>