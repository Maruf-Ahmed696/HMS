<?php
include '../models/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if(isset($data['offer_name']) && isset($data['promo_code'])) {
        $offerName = $data['offer_name'];
        $promoCode = $data['promo_code'];

        $stmt = $conn->prepare("INSERT INTO claimed_offers (offer_name, promo_code) VALUES (?, ?)");
        $stmt->bind_param("ss", $offerName, $promoCode);

        if ($stmt->execute()) {
            echo "Success! Offer Claimed: " . $offerName;
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error: Incomplete Data";
    }
}
$conn->close();
?>