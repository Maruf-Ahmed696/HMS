<?php
require_once '../Controllers/RoomController.php';
$controller = new RoomController();
echo json_encode($controller->handleAddRoom($_POST));
?>