<?php
require "../config/database.php";
require "../models/Report.php";

if($_SESSION['user']['role']!=='admin'){
    echo json_encode([]);
    exit;
}

$report = new Report($pdo);
echo json_encode($report->bookings());
