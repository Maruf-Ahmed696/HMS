<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room - Hotel Management</title>
    <link rel="stylesheet" href="guest\assets\css\style.css">
</head>
<body>
    <div class="center-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <h2>Book a Room</h2>
            <div>
                <a href="rooms.php" class="btn btn-outline btn-sm">Browse rooms</a>
                <a href="my_bookings.php" class="btn btn-info btn-sm">My Bookings</a>
            </div>
        </div>

        <div class="room-grid">
            <div>
                <div class="room-list">
                    <?php if (empty($availableRooms)): ?>
                        <div class="alert alert-error">No rooms available right now.</div>
                    <?php else: ?>
                        <?php foreach ($availableRooms as $room): ?>
                        <div class="room-item">
                            <div>
                                <strong><?php echo htmlspecialchars($room['room_number']); ?></strong>
                                <div style="color:#718096; font-size:13px"><?php echo htmlspecialchars($room['room_type']); ?></div>
                            </div>
                            <div style="text-align:right">
                                <div style="font-weight:700">$<?php echo number_format($room['price'],2); ?></div>
                                <div style="font-size:12px; color:#718096">Per night</div>
                            </div>
                            <div style="margin-left:12px">
                                <button class="btn btn-primary btn-sm" onclick="selectRoom(<?php echo $room['id']; ?>, '<?php echo htmlspecialchars($room['room_number']); ?>')">Select</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <form action="admin/controllers/BookingController.php?action=create" method="POST" id="userBookingForm">
                    <input type="hidden" name="from_user" value="1">
                    <input type="hidden" name="room_id" id="room_id" required>

                    <div class="form-row">
                        <label for="guest_name">Name</label>
                        <input type="text" id="guest_name" name="guest_name" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required>
                    </div>

                    <div class="form-row">
                        <label for="guest_email">Email</label>
                        <input type="email" id="guest_email" name="guest_email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
                    </div>

                    <div class="form-row">
                        <label for="guest_phone">Phone</label>
                        <input type="text" id="guest_phone" name="guest_phone" required>
                    </div>

                    <div class="form-row">
                        <label for="check_in">Check In</label>
                        <input type="date" id="check_in" name="check_in" required>
                    </div>

                    <div class="form-row">
                        <label for="check_out">Check Out</label>
                        <input type="date" id="check_out" name="check_out" required>
                    </div>

                    <div class="form-row">
                        <label for="guest_address">Address (optional)</label>
                        <input type="text" id="guest_address" name="guest_address">
                    </div>

                    <div class="form-row">
                        <label for="guest_id_number">ID Number (optional)</label>
                        <input type="text" id="guest_id_number" name="guest_id_number">
                    </div>

                    <div style="margin-top:12px; display:flex; gap:8px; align-items:center">
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                        <div id="selectedRoomInfo" style="color:#4a5568; font-size:13px">No room selected</div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function selectRoom(id, label) {
            const roomSelect = document.getElementById('room_id');
            if (roomSelect) {
                roomSelect.value = id;
            }
            document.getElementById('selectedRoomInfo').textContent = 'Selected: ' + label;
        }

        // simple minimum date setting
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('check_in').setAttribute('min', today);
        document.getElementById('check_out').setAttribute('min', today);

  
        (function() {
            const params = new URLSearchParams(window.location.search);
            const rid = params.get('room_id');
            if (rid) {
                const roomSelect = document.getElementById('room_id');
                if (roomSelect) {
                    const opt = roomSelect.querySelector('option[value="' + rid + '"]');
                    if (opt) {
                        roomSelect.value = rid;
                        document.getElementById('selectedRoomInfo').textContent = 'Selected: ' + opt.textContent;
                        window.scrollTo({ top: document.getElementById('userBookingForm').offsetTop - 20, behavior: 'smooth' });
                    }
                }
            }
        })();
    </script>
</body>
</html>