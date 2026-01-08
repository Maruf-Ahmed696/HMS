<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/Booking.php';

AuthController::requireLogin();

$bookingModel = new Booking();
$stats = $bookingModel->getStats();
$recentBookings = array_slice($bookingModel->getAll(), 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hotel Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include 'partials/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="top-bar">
                <h1>Dashboard</h1>
                <div>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo htmlspecialchars($_SESSION['success']); 
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Bookings</h3>
                    <div class="stat-value"><?php echo $stats['total_bookings']; ?></div>
                </div>
                
                <div class="stat-card success">
                    <h3>Confirmed Bookings</h3>
                    <div class="stat-value"><?php echo $stats['confirmed_bookings']; ?></div>
                </div>
                
                <div class="stat-card warning">
                    <h3>Available Rooms</h3>
                    <div class="stat-value"><?php echo $stats['available_rooms']; ?></div>
                </div>
                
                <div class="stat-card danger">
                    <h3>Total Revenue</h3>
                    <div class="stat-value">$<?php echo number_format($stats['total_revenue'], 2); ?></div>
                </div>
            </div>
            
            <div class="content-card">
                <div class="card-header">
                    <h2>Recent Bookings</h2>
                    <a href="bookings.php" class="btn btn-primary btn-sm">View All</a>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Guest Name</th>
                                <th>Room</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recentBookings) > 0): ?>
                                <?php foreach ($recentBookings as $booking): ?>
                                    <tr>
                                        <td><?php echo $booking['id']; ?></td>
                                        <td><?php echo htmlspecialchars($booking['guest_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['room_number']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></td>
                                        <td>$<?php echo number_format($booking['total_amount'], 2); ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = 'badge-secondary';
                                            if ($booking['status'] == 'confirmed') $badgeClass = 'badge-success';
                                            elseif ($booking['status'] == 'pending') $badgeClass = 'badge-warning';
                                            elseif ($booking['status'] == 'cancelled') $badgeClass = 'badge-danger';
                                            elseif ($booking['status'] == 'completed') $badgeClass = 'badge-info';
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?>">
                                                <?php echo ucfirst($booking['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center;">No bookings found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>