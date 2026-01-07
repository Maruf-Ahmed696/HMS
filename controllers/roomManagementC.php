<?php
require_once '../Models/db_Connect.php';

session_start();


$errors = [];
$confirmation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['roomType'])) {
        $errors['roomType'] = 'Room Type is required.';
    }
    if (empty($_POST['roomNumber']) || $_POST['roomNumber'] <= 0) {
        $errors['roomNumber'] = 'Room Number is required and must be a positive number!';
    }
    if (empty($_POST['roomAvailability'])) {
        $errors['roomAvailability'] = 'Room Availability is required!';
    }
    if (empty($_POST['roomStatus'])) {
        $errors['roomStatus'] = 'Room Status is required!';
    }


    if ($_POST['roomNumber'] < 5000 || $_POST['roomNumber'] > 5020) {
        $errors['roomNumber'] = 'Room Number must be between 5000 and 5020!';
    }


    if (empty($errors)) {

        $stmt = $conn->prepare("INSERT INTO rooms (room_type, room_number, room_availability, room_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_POST['roomType'], $_POST['roomNumber'], $_POST['roomAvailability'], $_POST['roomStatus']);


        if ($stmt->execute()) {

            $_SESSION['confirmation'] = [
                'roomType' => $_POST['roomType'],
                'roomNumber' => $_POST['roomNumber'],
                'roomAvailability' => $_POST['roomAvailability'],
                'roomStatus' => $_POST['roomStatus']
            ];


            setcookie("roomType", $_POST['roomType'], time() + 3600, "/"); // 1 hour expiry
            setcookie("roomNumber", $_POST['roomNumber'], time() + 3600, "/");
            setcookie("roomAvailability", $_POST['roomAvailability'], time() + 3600, "/");
            setcookie("roomStatus", $_POST['roomStatus'], time() + 3600, "/");


            header('Location: ../views/roommanageV.php');
            exit();
        } else {

            $_SESSION['errors'] = ['Database error: Could not save the room data.'];
            header('Location: ../views/roommanageV.php');
            exit();
        }
    } else {

        $_SESSION['errors'] = $errors;
        header('Location: ../views/roommanageV.php');
        exit();
    }
}
?>