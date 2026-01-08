<?php
require_once '../Models/PaymentModel.php';

class PayController
{
    public function handlePayment($data)
    {
        $errors = [];

        if (empty($data['payment_method']))
            $errors['method'] = "Please select a payment method.";
        if (empty($data['owner']))
            $errors['owner'] = "Owner name is required.";
        if (empty($data['number']))
            $errors['number'] = "Account/Card number is required.";
        if (empty($data['amount']) || !is_numeric($data['amount']))
            $errors['amount'] = "Valid amount is required.";

        if (!empty($data['payment_method'])) {
            if ($data['payment_method'] === 'Visa') {
                if (empty($data['securityCode'])) {
                    $errors['security'] = "CVC is required for Visa.";
                }
            } else {
                if (empty($data['securityCode'])) {
                    $errors['security'] = "PIN is required for " . $data['payment_method'] . ".";
                }
            }
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $saved = savePayment($data['payment_method'], $data['owner'], $data['number'], $data['amount']);

        if ($saved) {
            return [
                'success' => true,
                'message' => "Your Payment has done Successfully!!\n\nMethod: " . $data['payment_method'] . "\nOwner: " . $data['owner'] . "\nAmount: " . $data['amount'] . " BDT"
            ];
        }
        return ['success' => false, 'errors' => ['db' => 'Database error.']];
    }
}