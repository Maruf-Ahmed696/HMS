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
    <title>Staff Scheduling</title>
    <link rel="stylesheet" href="../Assets/css/staffschedCss.css">
</head>

<body>
    <div class="container">
        <h2>Staff Scheduling</h2>

        <form id="shiftForm" action="../controllers/staffSchedC.php" method="POST">
            <h3>Assign Shift</h3>


            <label for="staffName">Staff Name:</label>
            <input type="text" id="staffName" name="staffName"
                value="<?= isset($_POST['staffName']) ? $_POST['staffName'] : '' ?>">
            <span id="errorStaffName" class="error-message">
                <?php if (isset($errors['staffName'])): ?>
                    <?= $errors['staffName']; ?>
                <?php endif; ?>
            </span><br><br>


            <label for="shiftType">Shift Type:</label>
            <select id="shiftType" name="shiftType">
                <option value="">--Select Shift--</option>
                <option value="morning" <?= (isset($_POST['shiftType']) && $_POST['shiftType'] == 'morning') ? 'selected' : '' ?>>Morning</option>
                <option value="afternoon" <?= (isset($_POST['shiftType']) && $_POST['shiftType'] == 'afternoon') ? 'selected' : '' ?>>Afternoon</option>
                <option value="night" <?= (isset($_POST['shiftType']) && $_POST['shiftType'] == 'night') ? 'selected' : '' ?>>Night</option>
            </select>
            <span id="errorShiftType" class="error-message">
                <?php if (isset($errors['shiftType'])): ?>
                    <?= $errors['shiftType']; ?>
                <?php endif; ?>
            </span><br><br>


            <label for="startTime">Start Time:</label>
            <input type="time" id="startTime" name="startTime"
                value="<?= isset($_POST['startTime']) ? $_POST['startTime'] : '' ?>">
            <span id="errorStartTime" class="error-message">
                <?php if (isset($errors['startTime'])): ?>
                    <?= $errors['startTime']; ?>
                <?php endif; ?>
            </span><br><br>


            <label for="endTime">End Time:</label>
            <input type="time" id="endTime" name="endTime"
                value="<?= isset($_POST['endTime']) ? $_POST['endTime'] : '' ?>">
            <span id="errorEndTime" class="error-message">
                <?php if (isset($errors['endTime'])): ?>
                    <?= $errors['endTime']; ?>
                <?php endif; ?>
            </span><br><br>


            <label>
                <input type="checkbox" id="shiftNotification" name="shiftNotification"
                    <?= isset($_POST['shiftNotification']) && $_POST['shiftNotification'] == 'on' ? 'checked' : '' ?>>
                Notify staff about the shift
            </label><br><br>


            <button type="submit">Assign Shift</button>
            <p id="formError" style="color: red; display: none;">Please fill in all required fields.</p>
        </form>


        <?php if ($confirmation): ?>
            <div id="confirmation" style="display: block;">
                <h3>Shift Assigned Successfully</h3>
                <p>Staff Name: <span id="confirmationStaffName"><?= $confirmation['staffName'] ?></span></p>
                <p>Shift Type: <span id="confirmationShiftType"><?= $confirmation['shiftType'] ?></span></p>
                <p>Start Time: <span id="confirmationStartTime"><?= $confirmation['startTime'] ?></span></p>
                <p>End Time: <span id="confirmationEndTime"><?= $confirmation['endTime'] ?></span></p>
                <p>Shift Notification: <span
                        id="confirmationNotification"><?= $confirmation['shiftNotification'] ? 'Yes' : 'No' ?></span></p>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>