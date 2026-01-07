<?php
require "../config/database.php";
require "../models/Event.php";

if($_SESSION['user']['role']!=='admin'){
    echo json_encode(["status"=>"unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"),true);

$title = trim($data['title']);
$desc  = trim($data['desc']);
$date  = $data['date'];
$total = (int)$data['total'];

if($title==="" || $date==="" || $total<=0){
    echo json_encode(["status"=>"error"]);
    exit;
}

$event = new Event($pdo);
$event->create($title,$desc,$date,$total);

echo json_encode(["status"=>"success"]);
