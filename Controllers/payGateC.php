<?php
session_start();
require_once '../Models/payGateM.php';

$errors = [];
$method = $_POST['payment_method'] ?? '';
$_SESSION['method'] = $method;


if ($method == '') {
    $errors['method'] = "Please select a payment method";
}


if ($method == "Bkash" || $method == "Nagad" || $method == "Rocket") {

    if (empty($_POST['owner']))
        $errors['owner'] = "Number Owner Name is required";
    if (empty($_POST['number']))
        $errors['number'] = "Account Number is required";
    if (empty($_POST['amount']))
        $errors['amount'] = "Amount is required";
    if (empty($_POST['pin']))
        $errors['pin'] = "PIN is required";
}


if ($method == "Visa") {

    if (empty($_POST['owner']))
        $errors['owner'] = "Card Owner Name is required";
    if (empty($_POST['number']))
        $errors['number'] = "Card Number is required";
    if (empty($_POST['amount']))
        $errors['amount'] = "Amount is required";
    if (empty($_POST['cvc']))
        $errors['cvc'] = "CVC is required";
}


if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: ../Views/payGateV.php");
    exit;
}


insertPayment(
    $method,
    $_POST['owner'],
    $_POST['number'],
    $_POST['amount']
);


$_SESSION['success'] = [
    'method' => $method,
    'owner' => $_POST['owner'],
    'amount' => $_POST['amount']
];

unset($_SESSION['method']);
header("Location: ../Views/payGateV.php");
exit;
