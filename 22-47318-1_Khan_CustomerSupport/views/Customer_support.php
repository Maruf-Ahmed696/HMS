-<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>Customer Support</title>
    <link rel="stylesheet" href="../assets/Customer Support.css">
</head>
<body>

    <header class="navbar">
        <img src="../assets/About Us.png" alt="Logo" class="logo">
        <h1>Grand Hotel</h1>
    </header>

    <div class="main-wrapper">
        <div class="support-card">
            
            <div class="form-section">
                <h2>Need Help?</h2>
                <p>Fill out the form below to raise a ticket.</p>
                
                <input type="text" id="userName" placeholder="Your Full Name">
                <textarea id="userMsg" placeholder="Describe your issue..."></textarea>
                <button class="btn-submit" onclick="sendMsg()">Send Message</button>
            </div>

            <hr> 
            
            <div class="status-section">
                <h2>Track Ticket</h2>
                <div class="search-box">
                    <input type="text" id="ticketId" placeholder="Ticket ID (e.g. 101)">
                    <button class="btn-check" onclick="trackTicket()">Check</button>
                </div>
                <p id="statusResult"></p>
            </div>

        </div>
    </div>

    <script src="../assets/Customer Suoort.js"></script>

</body>
</html>