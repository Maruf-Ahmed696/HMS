<div class="sidebar">
    <div class="sidebar-header">
        <h3>🏨 Hotel Management</h3>
        <small>Admin Panel</small>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                📊 Dashboard
            </a>
        </li>
        <li>
            <a href="rooms.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'rooms.php' ? 'active' : ''; ?>">
                🛏️ Rooms
            </a>
        </li>
        <li>
            <a href="bookings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
                📅 Bookings
            </a>
        </li>
        <?php if ($_SESSION['role'] == 'admin'): ?>
        <li>
            <a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
                👥 Users
            </a>
        </li>
        <?php endif; ?>
        <li>
            <a href="../controllers/AuthController.php?action=logout">
                🚪 Logout
            </a>
        </li>
    </ul>
</div>