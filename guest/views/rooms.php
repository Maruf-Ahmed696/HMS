<?php
// expects $rooms to be set
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - Hotel Management</title>
    <link rel="stylesheet" href="/hotel_management/admin/assets/css/style.css">
    <style>
        .rooms-wrap { max-width:1100px; margin:40px auto; }
        .room-card { background:#fff; padding:16px; border-radius:8px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 8px 24px rgba(16,24,40,0.04); margin-bottom:12px }
        .room-meta { color:#4a5568 }
    </style>
</head>
<body>
    <div class="rooms-wrap">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px">
            <h2>Available Rooms</h2>
            <div>
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <a href="book.php" class="btn btn-primary btn-sm">Make a booking</a>
                    <a href="my_bookings.php" class="btn btn-info btn-sm">My Bookings</a>
                    <a href="admin/controllers/AuthController.php?action=logout" class="btn btn-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="register.php" class="btn btn-primary btn-sm">Sign Up</a>
                    <a href="login.php" class="btn btn-outline btn-sm">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($rooms)): ?>
            <div class="alert alert-error">No rooms found</div>
        <?php else: ?>
            <?php foreach ($rooms as $r): ?>
                <div class="room-card">
                    <div>
                        <div style="font-weight:700"><?php echo htmlspecialchars($r['room_number'] . ' - ' . $r['room_type']); ?></div>
                        <div class="room-meta"><?php echo htmlspecialchars($r['description'] ?? 'No description'); ?></div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-weight:800">$<?php echo number_format($r['price'],2); ?></div>
                        <div style="margin-top:8px">
                            <?php if ($r['status'] == 'available'): ?>
                                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                                    <a href="book.php?room_id=<?php echo $r['id']; ?>" class="btn btn-primary btn-sm">Book</a>
                                <?php else: ?>
                                    <?php $after = urlencode('book.php?room_id=' . $r['id']); ?>
                                    <a href="login.php?after=<?php echo $after; ?>" class="btn btn-info btn-sm">Login to book</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge badge-warning"><?php echo ucfirst($r['status']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>