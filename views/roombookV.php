<?php
session_start();


$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$confirmation = isset($_SESSION['confirmation']) ? $_SESSION['confirmation'] : null;

unset($_SESSION['errors']);
unset($_SESSION['confirmation']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking</title>
    <link rel="stylesheet" href="../Assets/css/roombookCss.css">
</head>

<body>
    <div class="container">
        <h2>Room Booking</h2>
        <form id="bookingForm" action="../controllers/roombookC.php" method="POST">

            <div>
                <label for="customerName">Customer Name:</label>
                <input type="text" id="customerName" name="customerName"
                    value="<?= isset($_POST['customerName']) ? $_POST['customerName'] : '' ?>">
                <?php if (isset($errors['customerName'])): ?>
                    <span class="error-message"><?= $errors['customerName'] ?></span>
                <?php endif; ?>
            </div>


            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email"
                    value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                <?php if (isset($errors['email'])): ?>
                    <span class="error-message"><?= $errors['email'] ?></span>
                <?php endif; ?>
            </div>


            <div>
                <label for="roomType">Room Type:</label>
                <select id="roomType" name="roomType">
                    <option value="">Select Room</option>
                    <option value="single" <?= (isset($_POST['roomType']) && $_POST['roomType'] == 'single') ? 'selected' : '' ?>>Single Room-1000 BDT</option>
                    <option value="double" <?= (isset($_POST['roomType']) && $_POST['roomType'] == 'double') ? 'selected' : '' ?>>Double Room-1500 BDT</option>
                    <option value="suite" <?= (isset($_POST['roomType']) && $_POST['roomType'] == 'suite') ? 'selected' : '' ?>>Suite Room-2500 BDT</option>
                </select>
                <?php if (isset($errors['roomType'])): ?>
                    <span class="error-message"><?= $errors['roomType'] ?></span>
                <?php endif; ?>
            </div>


            <div>
                <label for="bookingDays">Number of Days:</label>
                <input type="number" id="bookingDays" name="bookingDays"
                    value="<?= isset($_POST['bookingDays']) ? $_POST['bookingDays'] : '' ?>">
                <?php if (isset($errors['bookingDays'])): ?>
                    <span class="error-message"><?= $errors['bookingDays'] ?></span>
                <?php endif; ?>
            </div>


            <div>
                <label for="discountCode">Discount Code (optional):</label>
                <input type="text" id="discountCode" name="discountCode"
                    value="<?= isset($_POST['discountCode']) ? $_POST['discountCode'] : '' ?>">
            </div>


            <button type="submit">Submit</button>
        </form>


        <?php if ($confirmation): ?>
            <div id="confirmation" style="display: block;">
                <h3>Booking Confirmation</h3>
                <p><strong>Booking ID:</strong> <span id="bookingID"><?= uniqid('booking_', true) ?></span></p>
                <p><strong>Name:</strong> <span id="confirmationName"><?= $confirmation['customer_name'] ?></span></p>
                <p><strong>Booking Days:</strong> <span id="confirmationDays"><?= $confirmation['booking_days'] ?></span>
                </p>
                <p><strong>Room Type:</strong> <span
                        id="confirmationRoomType"><?= ucfirst($confirmation['room_type']) ?></span></p>
                <p><strong>Total Price (Before Discount):</strong> <span
                        id="confirmationOriginalPrice"><?= $confirmation['total_price'] ?></span> BDT</p>
                <p><strong>Total Price After Discount:</strong> <span
                        id="confirmationPrice"><?= $confirmation['total_price'] ?></span> BDT</p>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>