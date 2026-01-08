<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Offers</title>
    <link rel="stylesheet" href="../assets/special offers.css">
</head>
<body>
    <div class="header">
        <h1>Exclusive Special Offers</h1>
        <p>Grab your deal before it expires!</p>
    </div>
    <div class="container">
        <div class="card">
            <h3>Summer Vacation</h3>
            <p>50% Off on Suite Rooms</p>
            <div class="price">$100</div>
            <button onclick="claimOffer('Summer Vacation', 'SUMMER-50')">Claim Now</button>
        </div>
        <div class="card">
            <h3>Honeymoon Package</h3>
            <p>Free Candlelight Dinner</p>
            <div class="price">$250</div>
            <button onclick="claimOffer('Honeymoon Package', 'LOVE-2026')">Claim Now</button>
        </div>
    </div>
    <script>
        function claimOffer(name, code) {
            fetch('../controllers/offer_control.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ offer_name: name, promo_code: code })
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>