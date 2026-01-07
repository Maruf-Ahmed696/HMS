<?php
require "../config/database.php";
require "../models/User.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(["status" => "unauthorized"]);
    exit;
}

$user = new User($pdo);
$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? "";

/* LIST USERS */
if ($action === "list") {
    echo json_encode($user->getAll());
    exit;
}

/* UPDATE USER */
if ($action === "update") {
    $id    = (int)$data['id'];
    $name  = trim($data['name']);
    $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
    $role  = $data['role'];

    if (!$email || $name === "") {
        echo json_encode(["status" => "error"]);
        exit;
    }

    $user->update($id, $name, $email, $role);
    echo json_encode(["status" => "success"]);
    exit;
}

/* CHANGE STATUS */
if ($action === "status") {
    $user->changeStatus((int)$data['id'], $data['status']);
    echo json_encode(["status" => "success"]);
    exit;
}

/* DELETE USER */
if ($action === "delete") {
    $user->delete((int)$data['id']);
    echo json_encode(["status" => "success"]);
    exit;
}
