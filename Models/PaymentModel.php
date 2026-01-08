<?php
require_once 'db_connect.php';

function savePayment($method, $owner, $number, $amount)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO payments (payment_method, owner_name, account_number, amount) VALUES (?, ?, ?, ?)");

    $stmt->bind_param("sssd", $method, $owner, $number, $amount);

    $result = $stmt->execute();
    $stmt->close();

    return $result;
}
?>