<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (!empty($_SESSION['after_login'])) {
        $after = $_SESSION['after_login'];
        unset($_SESSION['after_login']);
        header('Location: ' . BASE_URL . $after);
        exit();
    }

    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header('Location: admin/views/dashboard.php');
    } else {
        header('Location: my_bookings.php');
    }
    exit();
}

if (isset($_GET['after']) && !empty($_GET['after'])) {

    $afterParam = ltrim($_GET['after'], '/');
    $_SESSION['after_login'] = $afterParam;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel Management System</title>
    <link rel="stylesheet" href="admin/assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>🏨 Hotel Management</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo htmlspecialchars($_SESSION['success']); 
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <form action="admin/controllers/AuthController.php?action=login" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            
            <p style="margin-top: 20px; text-align: center; color: #666; font-size: 14px;">
                Default credentials: admin / admin123
            </p>
            <p style="margin-top: 8px; text-align: center;">
                Don't have an account? <a href="register.php">Create one</a>
            </p>
        </div>
    </div>
    
    <script src="admin/assets/js/validation.js"></script>
</body>
</html>