<?php
require "../config/database.php";
require "../models/Reservation.php";

$data = json_decode(file_get_contents("php://input"),true);

$email = filter_var($data['email'],FILTER_VALIDATE_EMAIL);
$event = (int)$data['event'];
$seats = (int)$data['seats'];

if(!$email || $seats<=0){
    echo json_encode(["status"=>"error","msg"=>"Invalid input"]);
    exit;
}

$res = new Reservation($pdo);

if($res->reserve($event,$email,$seats)){
    echo json_encode(["status"=>"success"]);
}else{
    echo json_encode(["status"=>"error","msg"=>"Not enough seats"]);
}
