<?php
require_once '../Controllers/payGateC.php';
$controller = new PayController();
echo json_encode($controller->handlePayment($_POST));