<?php
include __DIR__ . '/../models/db.php';

$response = array("found" => false, "message" => "ID Not Found", "color" => "red");

if (isset($_POST['ticketId'])) {
    $id = $_POST['ticketId'];

    $stmt = $conn->prepare("SELECT status FROM support_tickets WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response["found"] = true;
        $response["message"] = "Ticket #" . $id . ": " . $row['status'];
        
        if ($row['status'] == 'Solved') {
            $response["color"] = "green";
        } else {
            $response["color"] = "orange";
        }
    }
}
echo json_encode($response);
?>