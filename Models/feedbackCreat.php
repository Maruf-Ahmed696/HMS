<?php
// Include the database connection file
require_once '../Models/db_Connect.php'; // Database connection

class FeedbackModel
{

    // Function to insert feedback data into the database
    public function insertFeedback($rating, $review)
    {
        global $conn; // Use the database connection from db_Connect.php

        // Prepare the SQL query to insert feedback
        $stmt = $conn->prepare("INSERT INTO feedback (rating, review) VALUES (?, ?)");
        $stmt->bind_param("is", $rating, $review); // Bind rating as integer, review as string

        // Execute the query
        if ($stmt->execute()) {
            return true; // If insertion was successful
        } else {
            return false; // If there was an error during insertion
        }
    }
}
?>