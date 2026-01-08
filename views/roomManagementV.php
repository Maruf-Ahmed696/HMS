<!DOCTYPE html>
<html lang="en">

<head>
    <title>Room Management</title>
    <link rel="stylesheet" href="../Assets/css/roommangCss.css">
    <style>
        .error-message {
            color: #ff0000 !important;
            font-weight: bold;
            display: block;
            min-height: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Room Management</h2>
        <form id="roomForm">
            <label>Room Type:</label>
            <select id="roomType">
                <option value="">--Select Room Type--</option>
                <option value="Single">Single Room - 1000 BDT</option>
                <option value="Double">Double Room - 1500 BDT</option>
                <option value="Suite">Suite - 2500 BDT</option>
            </select>
            <span id="errorRoomType" class="error-message"></span>

            <label>Room Number:</label>
            <input type="number" id="roomNumber" placeholder="Enter room number">
            <span id="errorRoomNumber" class="error-message"></span>

            <label>Availability:</label>
            <select id="roomAvailability">
                <option value="">--Select Availability--</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
            <span id="errorRoomAvailability" class="error-message"></span>

            <label>Room Status:</label>
            <select id="roomStatus">
                <option value="">--Select Status--</option>
                <option value="Clean">Clean</option>
                <option value="Dirty">Dirty</option>
            </select>
            <span id="errorRoomStatus" class="error-message"></span>

            <button type="button" id="addRoomBtn">Add Room</button>
        </form>
    </div>

    <script src="../Assets/js/roomManagement.js"></script>
</body>

</html>