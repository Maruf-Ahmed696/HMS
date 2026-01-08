<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Gateway</title>
    <link rel="stylesheet" href="../Assets/css/payGate.css">
</head>

<body>
    <div class="container">
        <form id="payForm">
            <h3>Payment Gateway</h3>

            <div class="method-selection">
                <label><input type="radio" name="payment_method" value="Bkash"> Bkash</label>
                <label><input type="radio" name="payment_method" value="Nagad"> Nagad</label>
                <label><input type="radio" name="payment_method" value="Rocket"> Rocket</label>
                <label><input type="radio" name="payment_method" value="Visa"> Visa Card</label>
            </div>
            <div class="error" id="methodErr"></div>

            <div id="dynamicFields" style="display:none; margin-top: 20px;">
                <input type="text" id="owner" placeholder="Owner Name">
                <div class="error" id="ownerErr"></div>

                <input type="text" id="number" placeholder="Account Number">
                <div class="error" id="numberErr"></div>

                <input type="text" id="amount" placeholder="Amount">
                <div class="error" id="amountErr"></div>

                <input type="password" id="securityCode" placeholder="PIN/CVC">
                <div class="error" id="securityErr"></div>

                <button type="button" id="payBtn">Complete Order</button>
            </div>
        </form>
    </div>
    <script src="../Assets/js/payGate.js"></script>
</body>

</html>