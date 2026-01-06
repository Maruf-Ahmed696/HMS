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
    <title>Room Management</title>
    <link rel="stylesheet" href="../Assets/css/roommangCss.css">
</head>

<body>
    <div class="container">
        <h2>Room Management</h2>
        <form id="roomManagementForm" action="../Controllers/roomManagementC.php" method="POST">

            <label for="roomType">Room Type:</label>
            <select id="roomType" name="roomType">
                <option value="">--Select Room Type--</option>
                <option value="single" <?= (isset($_POST['roomType']) || isset($_COOKIE['roomType']) && $_COOKIE['roomType'] == 'single') ? 'selected' : '' ?>>Single Room - 1000 BDT</option>
                <option value="double" <?= (isset($_POST['roomType']) || isset($_COOKIE['roomType']) && $_COOKIE['roomType'] == 'double') ? 'selected' : '' ?>>Double Room - 1500 BDT</option>
                <option value="suite" <?= (isset($_POST['roomType']) || isset($_COOKIE['roomType']) && $_COOKIE['roomType'] == 'suite') ? 'selected' : '' ?>>Suite - 2500 BDT</option>
            </select>
            <span id="errorRoomType" class="error-message">
                <?php if (isset($errors['roomType'])): ?>
                    <?= $errors['roomType']; ?>
                <?php endif; ?>
            </span><br><br>


            <label for="roomNumber">Room Number:</label>
            <input type="number" id="roomNumber" name="roomNumber" placeholder="Enter room number"
                value="<?= isset($_POST['roomNumber']) ? $_POST['roomNumber'] : (isset($_COOKIE['roomNumber']) ? $_COOKIE['roomNumber'] : '') ?>">
            <span id="errorRoomNumber" class="error-message">
                <?php if (isset($errors['roomNumber'])): ?>
                    <?= $errors['roomNumber']; ?>
                <?php endif; ?>
            </span><br><br>


            <label for="roomAvailability">Availability:</label>
            <select id="roomAvailability" name="roomAvailability">
                <option value="">--Select Availability--</option>
                <option value="yes" <?= (isset($_POST['roomAvailability']) || isset($_COOKIE['roomAvailability']) && $_COOKIE['roomAvailability'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                <option value="no" <?= (isset($_POST['roomAvailability']) || isset($_COOKIE['roomAvailability']) && $_COOKIE['roomAvailability'] == 'no') ? 'selected' : '' ?>>No</option>
            </select>
            <span id="errorRoomAvailability" class="error-message">
                <?php if (isset($errors['roomAvailability'])): ?>
                    <?= $errors['roomAvailability']; ?>
                <?php endif; ?>
            </span><br><br>


            <label for="roomStatus">Room Status:</label>
            <select id="roomStatus" name="roomStatus">
                <option value="">--Select Status--</option>
                <option value="clean" <?= (isset($_POST['roomStatus']) || isset($_COOKIE['roomStatus']) && $_COOKIE['roomStatus'] == 'clean') ? 'selected' : '' ?>>Clean</option>
                <option value="dirty" <?= (isset($_POST['roomStatus']) || isset($_COOKIE['roomStatus']) && $_COOKIE['roomStatus'] == 'dirty') ? 'selected' : '' ?>>Dirty</option>
            </select>
            <span id="errorRoomStatus" class="error-message">
                <?php if (isset($errors['roomStatus'])): ?>
                    <?= $errors['roomStatus']; ?>
                <?php endif; ?>
            </span><br><br>


            <button type="submit">Add Room</button>
        </form>


        <?php if ($confirmation): ?>
            <div id="confirmation" style="display: block;">
                <h3>Room Added Successfully</h3>
                <p>Room Type: <span id="confirmationRoomType"><?= $confirmation['roomType'] ?></span></p>
                <p>Room Number: <span id="confirmationRoomNumber"><?= $confirmation['roomNumber'] ?></span></p>
                <p>Room Availability: <span
                        id="confirmationRoomAvailability"><?= $confirmation['roomAvailability'] ?></span></p>
                <p>Room Status: <span id="confirmationRoomStatus"><?= $confirmation['roomStatus'] ?></span></p>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>