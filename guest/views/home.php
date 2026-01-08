<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Hotel Management</title>
    <link rel="stylesheet" href="guest\assets\css\style.css">

</head>
<body>
    <header style="display:flex; justify-content:space-between; align-items:center; max-width:1100px; margin:24px auto;">
        <div style="display:flex; align-items:center; gap:12px">
            <div style="font-size:20px; font-weight:700; color:#2d3748">🏨 Hotel Management</div>
            <nav style="margin-left:8px;">
                <a href="index.php" style="margin-right:10px; color:#4a5568;">Home</a>
                <a href="rooms.php" style="margin-right:10px; color:#4a5568;">Rooms</a>
                <a href="about.php" style="color:#4a5568;">About</a>
            </nav>
        </div>
        <div>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <span style="color:#4a5568; margin-right:8px">Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                <a href="my_bookings.php" class="btn btn-sm btn-info">My Bookings</a>
                <a href="admin/controllers/AuthController.php?action=logout" class="btn btn-sm btn-danger">Logout</a>
            <?php else: ?>
                <a href="register.php" class="btn btn-sm btn-primary">Sign Up</a>
                <a href="login.php" class="btn btn-sm btn-outline">Login</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="hero">
        <div class="hero-grid">
            <div>
                <h1>Stay comfortable. Book with confidence.</h1>
                <p>Discover clean rooms, thoughtful amenities, and friendly service. Browse rooms, sign up, or quickly book when you're ready.</p>

                <?php if (empty($_SESSION['logged_in'])): ?>
                <div class="cta-group">
                    <a href="register.php" class="btn btn-primary">Sign Up</a>
                    <a href="login.php" class="btn btn-outline">Login</a>
                    <a href="rooms.php" class="btn btn-info">Browse Rooms</a>
                </div>
                <?php else: ?>
                <div class="cta-group">
                    <a href="book.php" class="btn btn-primary">Book Now</a>
                    <a href="rooms.php" class="btn btn-info">Browse Rooms</a>
                </div>
                <?php endif; ?>

                <div class="nav-actions">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <span class="small-note">Signed in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                        <a href="my_bookings.php" class="btn btn-success btn-sm">My Bookings</a>
                        <a href="book.php" class="btn btn-primary btn-sm">Book Now</a>
                        <a href="admin/controllers/AuthController.php?action=logout" class="btn btn-danger btn-sm">Logout</a>
                    <?php else: ?>
                        <span class="small-note">Or continue without an account and browse rooms</span>
                        <a href="rooms.php" class="btn btn-info btn-sm">Browse Rooms</a>
                    <?php endif; ?>
                </div>

                <div class="feature-row">
                    <div class="feature">
                        <h3>Best Rates</h3>
                        <p>Competitive pricing and transparent fees.</p>
                    </div>
                    <div class="feature">
                        <h3>Instant Confirmation</h3>
                        <p>Secure bookings with immediate confirmation details.</p>
                    </div>
                    <div class="feature">
                        <h3>Manage Easily</h3>
                        <p>View and cancel bookings from your account.</p>
                    </div>
                </div>
            </div>

            <div class="hero-right" style="display:flex; align-items:center; justify-content:center;">
                <div style="background:linear-gradient(135deg,#667eea,#764ba2); width:260px; height:260px; border-radius:18px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; text-align:center; padding:14px; box-shadow:0 12px 40px rgba(103,58,183,0.12)">Your next comfortable stay awaits</div>
            </div>
        </div>
    </div>
</body>
</html>