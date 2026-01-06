<?php
/*
 Entry point of the project
 If user is logged in → redirect to dashboard (Event Management)
 If not logged in → show simple login form
*/

session_start();

if (isset($_SESSION['user'])) {
    header("Location: views/EventManagement.html");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Grand Hotel Management System - Login</title>
</head>
<body>

<h2>Grand Hotel Management System</h2>
<h3>Login</h3>

<form id="loginForm">
    <input type="email" id="email" name="email" placeholder="Email" required>
    <input type="password" id="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<p id="loginMsg"></p>

<script>
document.getElementById("loginForm").addEventListener("submit", function(e){
    e.preventDefault();

    fetch("auth/login.php",{
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "email=" + encodeURIComponent(email.value) +
              "&password=" + encodeURIComponent(password.value)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "success"){
            window.location.href = "views/EventManagement.html";
        }else{
            document.getElementById("loginMsg").innerText = "Login failed";
        }
    });
});
</script>

</body>
</html>
