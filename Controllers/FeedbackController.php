<?php
require_once '../Models/FeedbackModel.php';

class FeedbackController
{
    public function handleFeedback($data)
    {
        $errors = [];

        if (empty($data['rating'])) {
            $errors['rating'] = "Please select a star rating.";
        }
        if (empty($data['review'])) {
            $errors['review'] = "Please write a comment.";
        } elseif (strlen($data['review']) < 5) {
            $errors['review'] = "Review must be at least 5 characters.";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $inserted = saveFeedback($data['rating'], $data['review']);

        if ($inserted) {
            return [
                'success' => true,
                'message' => "Thank you for your feedback!\n\nRating: " . $data['rating'] . " Stars\nReview: " . $data['review']
            ];
        }

        return ['success' => false, 'errors' => ['db' => 'Database error.']];
    }
}
?>