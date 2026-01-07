<?php

require_once '../Models/db_Connect.php';
require_once '../Models/feedbackCreat.php';

session_start();


$errors = [];
$confirmation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['rating'])) {
        $errors['rating'] = 'Please select a rating!';
    }
    if (empty($_POST['review'])) {
        $errors['review'] = 'Please write a review!';
    }

    if (empty($errors)) {

        $feedbackModel = new FeedbackModel();

        $isInserted = $feedbackModel->insertFeedback($_POST['rating'], $_POST['review']);

        if ($isInserted) {

            $_SESSION['confirmation'] = [
                'rating' => $_POST['rating'],
                'review' => $_POST['review']
            ];


            header('Location: ../views/feedbackV.php');
            exit();
        } else {
            $_SESSION['errors'] = ['Database error: Could not save the review.'];
            header('Location: ../views/feedbackV.php');
            exit();
        }
    } else {
        $_SESSION['errors'] = $errors;
        header('Location: ../views/feedbackV.php');
        exit();
    }
}
?>