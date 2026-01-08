<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Room.php';

AuthController::requireLogin();

$bookingModel = new Booking();
$roomModel = new Room();
$bookings = $bookingModel->getAll();
$availableRooms = $roomModel->getAvailable();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - Hotel Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include 'partials/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="top-bar">
                <h1>Bookings Management</h1>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <div class="content-card">
                <div class="card-header">
                    <h2>All Bookings</h2>
                    <button class="btn btn-success btn-sm" onclick="openAddModal()">+ New Booking</button>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Guest Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Room</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?php echo $booking['id']; ?></td>
                                    <td><?php echo htmlspecialchars($booking['guest_name']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['guest_email']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['guest_phone']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['room_number']); ?> (<?php echo ucfirst($booking['room_type']); ?>)</td>
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
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-info btn-sm" onclick='viewBooking(<?php echo json_encode($booking); ?>)'>View</button>
                                            <button class="btn btn-warning btn-sm" onclick='updateStatus(<?php echo $booking['id']; ?>, "<?php echo $booking['status']; ?>")'>Status</button>
                                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                                <a href="../controllers/BookingController.php?action=delete&id=<?php echo $booking['id']; ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('Are you sure?')">Delete</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>New Booking</h3>
                <span class="close-modal" onclick="closeModal('bookingModal')">&times;</span>
            </div>
            
            <form action="../controllers/BookingController.php?action=create" method="POST" id="bookingForm">
                <h4 style="margin-bottom: 15px; color: #667eea;">Guest Information</h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="guest_name">Guest Name *</label>
                        <input type="text" name="guest_name" id="guest_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="guest_email">Email *</label>
                        <input type="email" name="guest_email" id="guest_email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="guest_phone">Phone *</label>
                        <input type="tel" name="guest_phone" id="guest_phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="guest_id_number">ID Number</label>
                        <input type="text" name="guest_id_number" id="guest_id_number">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="guest_address">Address</label>
                    <textarea name="guest_address" id="guest_address" rows="2" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                </div>
                
                <h4 style="margin: 20px 0 15px; color: #667eea;">Booking Details</h4>
                
                <div class="form-group">
                    <label for="room_id">Select Room *</label>
                    <select name="room_id" id="room_id" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;" onchange="calculatePrice()">
                        <option value="">-- Select Room --</option>
                        <?php foreach ($availableRooms as $room): ?>
                            <option value="<?php echo $room['id']; ?>" data-price="<?php echo $room['price']; ?>">
                                <?php echo htmlspecialchars($room['room_number']); ?> - 
                                <?php echo ucfirst($room['room_type']); ?> 
                                ($<?php echo number_format($room['price'], 2); ?>/night)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="check_in">Check In *</label>
                        <input type="date" name="check_in" id="check_in" required onchange="calculatePrice()">
                    </div>
                    
                    <div class="form-group">
                        <label for="check_out">Check Out *</label>
                        <input type="date" name="check_out" id="check_out" required onchange="calculatePrice()">
                    </div>
                </div>
                
                <div id="priceCalculation" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; display: none;">
                    <p style="margin: 5px 0;"><strong>Nights:</strong> <span id="nights">0</span></p>
                    <p style="margin: 5px 0;"><strong>Price per night:</strong> $<span id="pricePerNight">0.00</span></p>
                    <p style="margin: 5px 0;"><strong>Total Amount:</strong> $<span id="totalAmount">0.00</span></p>
                </div>
                
                <button type="submit" class="btn btn-primary">Create Booking</button>
            </form>
        </div>
    </div>
    
    <!-- View Booking Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Booking Details</h3>
                <span class="close-modal" onclick="closeModal('viewModal')">&times;</span>
            </div>
            <div id="viewContent"></div>
        </div>
    </div>
    
    <!-- Status Update Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Booking Status</h3>
                <span class="close-modal" onclick="closeModal('statusModal')">&times;</span>
            </div>
            
            <form action="../controllers/BookingController.php?action=update_status" method="POST">
                <input type="hidden" name="id" id="statusBookingId">
                
                <div class="form-group">
                    <label for="status">New Status *</label>
                    <select name="status" id="statusSelect" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/bookings.js"></script>
</body>
</html>