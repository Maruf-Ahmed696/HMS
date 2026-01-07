<?php
require "../config/database.php";

$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? "";

if (!$email || !$password) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

$stmt = $pdo->prepare(
    "SELECT * FROM users WHERE email = ? AND status = 'active'"
);
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        "id"   => $user['id'],
        "name" => $user['name'],
        "role" => $user['role']
    ];

    echo json_encode([
        "status" => "success",
        "role"   => $user['role']
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Login failed"
    ]);
}
