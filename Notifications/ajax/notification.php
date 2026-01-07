<?php
require "../config/database.php";
require "../models/Notification.php";

$notification = new Notification($pdo);
$data = json_decode(file_get_contents("php://input"),true);
$action = $data['action'] ?? "";


if($action === "send"){
    if($_SESSION['user']['role'] !== 'admin'){
        echo json_encode(["status"=>"unauthorized"]);
        exit;
    }

    $type = htmlspecialchars($data['type']);
    $msg  = htmlspecialchars($data['message']);

    if(trim($msg)===""){
        echo json_encode(["status"=>"error"]);
        exit;
    }

    $notification->create($type,$msg);
    echo json_encode(["status"=>"success"]);
    exit;
}

echo json_encode($notification->all());
