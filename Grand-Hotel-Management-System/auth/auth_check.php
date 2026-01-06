<?php
require "../config/database.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}
