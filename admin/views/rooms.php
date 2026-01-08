<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/Room.php';

AuthController::requireLogin();

$roomModel = new Room();
$rooms = $roomModel->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - Hotel Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include 'partials/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="top-bar">
                <h1>Rooms Management</h1>
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
                    <h2>All Rooms</h2>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <button class="btn btn-success btn-sm" onclick="openAddModal()">+ Add Room</button>
                    <?php endif; ?>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Room Number</th>
                                <th>Type</th>
                                <th>Price/Night</th>
                                <th>Status</th>
                                <th>Description</th>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                                    <td><?php echo ucfirst($room['room_type']); ?></td>
                                    <td>$<?php echo number_format($room['price'], 2); ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = 'badge-secondary';
                                        if ($room['status'] == 'available') $badgeClass = 'badge-success';
                                        elseif ($room['status'] == 'booked') $badgeClass = 'badge-warning';
                                        elseif ($room['status'] == 'maintenance') $badgeClass = 'badge-danger';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst($room['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($room['description'], 0, 50)); ?>...</td>
                                    <?php if ($_SESSION['role'] == 'admin'): ?>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-warning btn-sm" onclick='editRoom(<?php echo json_encode($room); ?>)'>Edit</button>
                                                <a href="../controllers/RoomController.php?action=delete&id=<?php echo $room['id']; ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('Are you sure?')">Delete</a>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Modal -->
    <div id="roomModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add Room</h3>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            
            <form id="roomForm" method="POST">
                <input type="hidden" name="id" id="roomId">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="room_number">Room Number *</label>
                        <input type="text" name="room_number" id="room_number" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="room_type">Room Type *</label>
                        <select name="room_type" id="room_type" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="single">Single</option>
                            <option value="double">Double</option>
                            <option value="suite">Suite</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price/Night *</label>
                        <input type="number" name="price" id="price" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select name="status" id="status" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="available">Available</option>
                            <option value="booked">Booked</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Save Room</button>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/rooms.js"></script>
</body>
</html>