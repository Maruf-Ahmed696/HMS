<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Staff Scheduling</title>
    <link rel="stylesheet" href="../Assets/css/staffschedCss.css">
</head>

<body>
    <div class="container">
        <h2>Staff Scheduling</h2>
        <form id="shiftForm">
            <h3>Assign Shift</h3>

            <label>Staff Name:</label>
            <input type="text" id="staffName" placeholder="Enter name">
            <span id="errorStaffName" class="error-message"></span>

            <label>Shift Type:</label>
            <select id="shiftType">
                <option value="">--Select Shift--</option>
                <option value="morning">Morning</option>
                <option value="afternoon">Afternoon</option>
                <option value="night">Night</option>
            </select>
            <span id="errorShiftType" class="error-message"></span>

            <label>Start Time:</label>
            <input type="time" id="startTime">
            <span id="errorStartTime" class="error-message"></span>

            <label>End Time:</label>
            <input type="time" id="endTime">
            <span id="errorEndTime" class="error-message"></span>

            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" id="shiftNotification" style="width: auto; margin: 0;">
                Notify staff about shift
            </label><br>

            <button type="button" id="assignBtn">Assign Shift</button>
        </form>
    </div>

    <script src="../Assets/js/staffScheduling.js"></script>
</body>

</html>