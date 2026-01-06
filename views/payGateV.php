<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $_SESSION['method'] = $_POST['payment_method'];
}

$method = $_SESSION['method'] ?? '';
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? null;

unset($_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../Assets/css/payGate.css">
</head>

<body>

    <div class="container">


        <form method="POST">
            <h3>Payment Gateway</h3>

            <label><input type="radio" name="payment_method" value="Bkash" <?= ($method == "Bkash") ? 'checked' : '' ?>>
                Bkash</label>
            <label><input type="radio" name="payment_method" value="Nagad" <?= ($method == "Nagad") ? 'checked' : '' ?>>
                Nagad</label>
            <label><input type="radio" name="payment_method" value="Rocket" <?= ($method == "Rocket") ? 'checked' : '' ?>>
                Rocket</label>
            <label><input type="radio" name="payment_method" value="Visa" <?= ($method == "Visa") ? 'checked' : '' ?>> Visa
                Card</label>

            <p class="error">
                <?= $errors['method'] ?? '' ?>
            </p>

            <button type="submit">Proceed</button>
        </form>

        <?php if ($method != '') { ?>
            <form action="../Controllers/payGateC.php" method="POST">

                <input type="hidden" name="payment_method" value="<?= $method ?>">

                <input type="text" name="owner"
                    placeholder="<?= $method == 'Visa' ? 'Card Owner Name' : 'Number Owner Name' ?>">
                <p class="error">
                    <?= $errors['owner'] ?? '' ?>
                </p>

                <input type="text" name="number" placeholder="<?= $method == 'Visa' ? 'Card Number' : 'Account Number' ?>">
                <p class="error">
                    <?= $errors['number'] ?? '' ?>
                </p>

                <input type="text" name="amount" placeholder="Amount">
                <p class="error">
                    <?= $errors['amount'] ?? '' ?>
                </p>

                <?php if ($method == "Visa") { ?>
                    <input type="password" name="cvc" placeholder="CVC">
                    <p class="error">
                        <?= $errors['cvc'] ?? '' ?>
                    </p>
                <?php } else { ?>
                    <input type="password" name="pin" placeholder="PIN">
                    <p class="error">
                        <?= $errors['pin'] ?? '' ?>
                    </p>
                <?php } ?>

                <button type="submit">Complete Order</button>
            </form>
        <?php } ?>


        <?php if ($success) { ?>
            <div class="success">
                <p>Your Payment has done Successfully!!</p>
                <p>Payment Method :
                    <?= $success['method'] ?>
                </p>
                <p>Owner Name :
                    <?= $success['owner'] ?>
                </p>
                <p>Amount :
                    <?= $success['amount'] ?> BDT
                </p>
            </div>
        <?php } ?>

    </div>

</body>

</html>