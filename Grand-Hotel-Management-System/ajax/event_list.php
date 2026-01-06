<?php
require "../config/database.php";
require "../models/Event.php";

$event = new Event($pdo);
echo json_encode($event->all());
