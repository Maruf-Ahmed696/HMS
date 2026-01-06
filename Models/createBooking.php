<?php
require_once '../Models/db_Connect.php';


session_start();


$errors = [];
$confirmation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['customerName'])) {
        $errors['customerName'] = 'Customer name is required.';
    }
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Valid email is required.';
    }
    if (empty($_POST['roomType'])) {
        $errors['roomType'] = 'Room type is required.';
    }
    if (empty($_POST['bookingDays']) || $_POST['bookingDays'] <= 0) {
        $errors['bookingDays'] = 'Number of booking days is required.';
    }


    if (empty($errors)) {

        $totalPrice = 0;
        switch ($_POST['roomType']) {
            case 'single':
                $totalPrice = 1000 * $_POST['bookingDays'];
                break;
            case 'double':
                $totalPrice = 1500 * $_POST['bookingDays'];
                break;
            case 'suite':
                $totalPrice = 2500 * $_POST['bookingDays'];
                break;
        }


        $stmt = $conn->prepare("INSERT INTO bookings (customer_name, email, room_type, booking_days, total_price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $_POST['customerName'], $_POST['email'], $_POST['roomType'], $_POST['bookingDays'], $totalPrice);

        if ($stmt->execute()) {

            $_SESSION['confirmation'] = [
                'customer_name' => $_POST['customerName'],
                'booking_days' => $_POST['bookingDays'],
                'room_type' => $_POST['roomType'],
                'total_price' => $totalPrice
            ];


            header('Location: ../views/roombookV.php');
            exit();
        } else {
            $_SESSION['errors'] = ['Database error: Could not save the booking.'];
            header('Location: ../views/roombookV.php');
            exit();
        }
    } else {

        $_SESSION['errors'] = $errors;
        header('Location: ../views/roombookV.php');
        exit();
    }
}
?>