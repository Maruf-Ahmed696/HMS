<?php
require_once '../Controllers/StaffController.php';
$controller = new StaffController();
echo json_encode($controller->handleShiftAssignment($_POST));
?>