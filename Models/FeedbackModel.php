<?php
require_once 'db_connect.php';

function saveFeedback($rating, $review)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO feedback (rating, review) VALUES (?, ?)");
    $stmt->bind_param("is", $rating, $review);

    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>