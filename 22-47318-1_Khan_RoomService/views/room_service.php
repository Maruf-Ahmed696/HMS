<!DOCTYPE html>
<html>
<head>
    <title>Room Service</title>
    <link rel="stylesheet" href="../assets/Room Service.css">
</head>
<body>

    <div class="container">
        <img src="../assets/About Us.png" alt="Logo" class="logo">
        <h2>Grand Hotel: Room Service</h2>

        <input type="text" id="room" placeholder="Enter Room Number">
        
        <select id="service">
            <option>Room Cleaning</option>
            <option>Fan Repair</option>
            <option>Water Tap Problem</option>
            <option>Furniture Repair</option>
            <option>WiFi Internet Issue</option>
            <option>Light Bulb Change</option>
            <option>Laundry Pickup</option>
            <option>Order Dinner</option>
        </select>

        <button onclick="addRequest()">Send Request</button>

        <h3>Request List:</h3>
        <ul id="list"></ul>
    </div>

    <script src="../assets/room_service.js"></script>
</body>
</html>