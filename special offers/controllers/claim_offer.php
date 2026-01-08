<?php
include __DIR__ . '/../models/db.php';
header('Content-Type: application/json');

$response = array("success" => false, "message" => "Something went wrong");

if (isset($_POST['offer_name'])) {
    $offerName = $_POST['offer_name'];
    
    $promoCode = "OFFER-" . rand(1000, 9999);

    $stmt = $conn->prepare("INSERT INTO claimed_offers (offer_name, promo_code) VALUES (?, ?)");
    $stmt->bind_param("ss", $offerName, $promoCode);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = " Code Generated: " . $promoCode . "\n(Saved to Database)";
    } else {
        $response["message"] = "Database Error: " . $conn->error;
    }
}

echo json_encode($response);
?>