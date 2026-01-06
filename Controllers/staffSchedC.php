<?php
require_once '../Models/db_Connect.php';
require_once '../Models/createStaff.php';

session_start();


$errors = [];
$confirmation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form validation logic
    if (empty($_POST['staffName'])) {
        $errors['staffName'] = 'Staff Name is required.';
    }
    if (empty($_POST['shiftType'])) {
        $errors['shiftType'] = 'Shift Type is required.';
    }
    if (empty($_POST['startTime'])) {
        $errors['startTime'] = 'Start Time is required.';
    }
    if (empty($_POST['endTime'])) {
        $errors['endTime'] = 'End Time is required.';
    }


    if (empty($errors)) {
        $staffModel = new CreateStaffModel();

        $shiftNotification = isset($_POST['shiftNotification']) ? 'on' : 'off';
        $isInserted = $staffModel->insertStaffSchedule($_POST['staffName'], $_POST['shiftType'], $_POST['startTime'], $_POST['endTime'], $shiftNotification);

        if ($isInserted) {
            $_SESSION['confirmation'] = [
                'staffName' => $_POST['staffName'],
                'shiftType' => $_POST['shiftType'],
                'startTime' => $_POST['startTime'],
                'endTime' => $_POST['endTime'],
                'shiftNotification' => isset($_POST['shiftNotification']) ? true : false
            ];


            header('Location: ../views/staffSchedV.php');
            exit();
        } else {

            $_SESSION['errors'] = ['Database error: Could not save the shift data.'];
            header('Location: ../views/staffSchedV.php');
            exit();
        }
    } else {
        $_SESSION['errors'] = $errors;
        header('Location: ../views/staffSchedV.php');
        exit();
    }
}
?>