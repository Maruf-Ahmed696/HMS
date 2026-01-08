<?php
include __DIR__ . '/../models/db.php';
$response = array("success" => false, "message" => "Error");

if (isset($_POST['userName']) && isset($_POST['userMsg'])) {
    
    $name = $_POST['userName'];
    $msg = $_POST['userMsg'];

    if ($name == "" || $msg == "") {
        $response["message"] = "Please fill all fields!";
    } else {
        $stmt = $conn->prepare("INSERT INTO support_tickets (user_name, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $msg);
        
        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Ticket Sent! ID: " . $stmt->insert_id;
        }
    }
}
echo json_encode($response);
?>