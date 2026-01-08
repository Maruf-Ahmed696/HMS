<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/admin/controllers/AuthController.php';
require_once __DIR__ . '/admin/models/Booking.php';

AuthController::requireLogin();

$bookingModel = new Booking();
$bookings = [];
if (isset($_SESSION['email'])) {
    $bookings = $bookingModel->getByGuestEmail($_SESSION['email']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Hotel Management</title>
    <link rel="stylesheet" href="admin/assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="main-content" style="margin: 40px auto; max-width: 1000px;">
            <div class="top-bar">
                <h1>My Bookings</h1>
                <div style="float:right;"><a href="admin/controllers/AuthController.php?action=logout" class="btn btn-danger btn-sm">Logout</a></div>
                <div style="float:left;"><a href="book.php" class="btn btn-success">Make a new booking</a></div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="content-card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Room</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bookings)): ?>
                                <tr><td colspan="7" style="text-align:center;">No bookings found</td></tr>
                            <?php else: ?>
                                <?php foreach ($bookings as $b): ?>
                                    <tr>
                                        <td><?php echo $b['id']; ?></td>
                                        <td><?php echo htmlspecialchars($b['room_number'] . ' (' . $b['room_type'] . ')'); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($b['check_in'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($b['check_out'])); ?></td>
                                        <td>$<?php echo number_format($b['total_amount'], 2); ?></td>
                                        <td><?php echo ucfirst($b['status']); ?></td>
                                        <td>
                                            <?php if ($b['status'] != 'cancelled' && $b['status'] != 'completed'): ?>
                                                <form action="admin/controllers/BookingController.php?action=update_status" method="POST" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?php echo $b['id']; ?>">
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <input type="hidden" name="from_user" value="1">
                                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="badge badge-info">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>