<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Booking</title>
    <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>

<div id="bookingForm">
    <h2>Room Booking</h2>
    
    <input type="text" id="name" placeholder="Customer Name">
    <div class="error" id="nameErr"></div>

    <input type="text" id="contact" placeholder="Email or Phone Number">
    <div class="error" id="contactErr"></div>

    <select id="roomType">
        <option value="">Select Room Type</option>
        <option value="Single">Single - 1000 BDT</option>
        <option value="Double">Double - 1800 BDT</option>
        <option value="Family">Family - 3000 BDT</option>
    </select>
    <div class="error" id="roomErr"></div>

    <input type="number" id="days" placeholder="Days for room">
    <div class="error" id="daysErr"></div>

    <input type="text" id="coupon" placeholder="DISCOUNT COUPON">

    <button type="button" id="bookBtn">Book Room</button>
</div>

<script src="../Assets/js/script.js"></script>
</body>
</html>