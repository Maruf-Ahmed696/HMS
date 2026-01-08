<?php
require_once '../Controllers/FeedbackController.php';
$controller = new FeedbackController();
echo json_encode($controller->handleFeedback($_POST));
?>