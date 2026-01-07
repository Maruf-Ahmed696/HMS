<?php
require_once 'db_Connect.php';

function insertPayment($method, $owner, $number, $amount) {
    global $conn;
    $sql = "INSERT INTO payment_gateway 
            (payment_method, owner_name, account_number, amount)
            VALUES ('$method', '$owner', '$number', '$amount')";
    return mysqli_query($conn, $sql);
}
?>
