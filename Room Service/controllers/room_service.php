<?php

include __DIR__ . '/../models/db.php';

header('Content-Type: application/json');
$response = array("success" => false, "message" => "Error");

if (isset($_POST['room']) && isset($_POST['service'])) {
    
    $room = $_POST['room'];
    $service = $_POST['service'];

    if ($room == "") {
        $response["message"] = "Room number must be fill up!";
    } else {
        
        $stmt = $conn->prepare("INSERT INTO room_service (room_number, service_type) VALUES (?, ?)");
        $stmt->bind_param("ss", $room, $service);
        
        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Request Sent! Order ID: " . $stmt->insert_id;
        } else {
            $response["message"] = "Problem in Database!";
        }
    }
}

echo json_encode($response);
?>