<?php
require_once '../Models/BookingModel.php';

class BookingController
{

    public function handleBooking($postData)
    {
        $errors = [];

        if (empty($postData['name']))
            $errors['name'] = "Customer Name is required.";
        if (empty($postData['contact']))
            $errors['contact'] = "Email or Phone Number is required.";
        if (empty($postData['roomType']))
            $errors['roomType'] = "Please select a room type.";
        if (empty($postData['days']) || $postData['days'] <= 0)
            $errors['days'] = "Invalid number of days.";

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $priceMap = ["Single" => 1000, "Double" => 1800, "Family" => 3000];
        $totalPrice = $priceMap[$postData['roomType']] * intval($postData['days']);

        if (!empty($postData['coupon'])) {
            $totalPrice *= 0.9;
        }
        $inserted = insertBooking(
            $postData['name'],
            $postData['contact'],
            $postData['roomType'],
            intval($postData['days']),
            $totalPrice
        );

        if ($inserted) {
            return [
                'success' => true,
                'message' => "your booking is in progress.....\nPlease wait \"" . $postData['name'] . "\"\nRoom Type: " . $postData['roomType'] . "\nTotal Price: " . number_format($totalPrice, 2) . " BDT"
            ];
        } else {
            return ['success' => false, 'errors' => ['db' => 'Database error occurred.']];
        }
    }
}
?>