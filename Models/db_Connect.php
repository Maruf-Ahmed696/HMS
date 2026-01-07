<?php
// db_Connect.php - Database connection file

$servername = "localhost";
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "hotel_management";  // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>