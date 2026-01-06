<?php
require "../config/database.php";

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=report.csv");

$out = fopen("php://output","w");
fputcsv($out,["Date","Seats"]);

$q = $pdo->query(
    "SELECT DATE(reserved_at), SUM(seats)
     FROM reservations GROUP BY DATE(reserved_at)"
);

while($row = $q->fetch()){
    fputcsv($out,$row);
}
fclose($out);
