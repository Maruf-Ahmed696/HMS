// Open add booking modal
function openAddModal() {
    document.getElementById('bookingForm').reset();
    document.getElementById('priceCalculation').style.display = 'none';
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('check_in').setAttribute('min', today);
    document.getElementById('check_out').setAttribute('min', today);
    
    document.getElementById('bookingModal').classList.add('active');
}

// View booking details
function viewBooking(booking) {
    const content = `
        <div style="line-height: 1.8;">
            <h4 style="color: #667eea; margin-bottom: 15px;">Guest Information</h4>
            <p><strong>Name:</strong> ${booking.guest_name}</p>
            <p><strong>Email:</strong> ${booking.guest_email}</p>
            <p><strong>Phone:</strong> ${booking.guest_phone}</p>
            <p><strong>Address:</strong> ${booking.guest_address || 'N/A'}</p>
            <p><strong>ID Number:</strong> ${booking.guest_id_number || 'N/A'}</p>
            
            <h4 style="color: #667eea; margin: 20px 0 15px;">Booking Details</h4>
            <p><strong>Room:</strong> ${booking.room_number} (${booking.room_type})</p>
            <p><strong>Check In:</strong> ${new Date(booking.check_in).toLocaleDateString()}</p>
            <p><strong>Check Out:</strong> ${new Date(booking.check_out).toLocaleDateString()}</p>
            <p><strong>Total Amount:</strong> $${parseFloat(booking.total_amount).toFixed(2)}</p>
            <p><strong>Status:</strong> ${booking.status.toUpperCase()}</p>
            <p><strong>Booked On:</strong> ${new Date(booking.created_at).toLocaleString()}</p>
        </div>
    `;
    
    document.getElementById('viewContent').innerHTML = content;
    document.getElementById('viewModal').classList.add('active');
}

// Update status modal
function updateStatus(id, currentStatus) {
    document.getElementById('statusBookingId').value = id;
    document.getElementById('statusSelect').value = currentStatus;
    document.getElementById('statusModal').classList.add('active');
}

// Close modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Calculate price using AJAX
function calculatePrice() {
    const roomSelect = document.getElementById('room_id');
    const checkIn = document.getElementById('check_in').value;
    const checkOut = document.getElementById('check_out').value;
    
    if (roomSelect.value && checkIn && checkOut) {
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price);
        
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        
        if (checkOutDate <= checkInDate) {
            alert('Check-out date must be after check-in date');
            document.getElementById('check_out').value = '';
            document.getElementById('priceCalculation').style.display = 'none';
            return;
        }
        
        const timeDiff = checkOutDate - checkInDate;
        const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
        const total = price * nights;
        
        document.getElementById('nights').textContent = nights;
        document.getElementById('pricePerNight').textContent = price.toFixed(2);
        document.getElementById('totalAmount').textContent = total.toFixed(2);
        document.getElementById('priceCalculation').style.display = 'block';
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            const guestName = document.getElementById('guest_name').value.trim();
            const guestEmail = document.getElementById('guest_email').value.trim();
            const guestPhone = document.getElementById('guest_phone').value.trim();
            const roomId = document.getElementById('room_id').value;
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            
            // Validate required fields
            if (!guestName || !guestEmail || !guestPhone || !roomId || !checkIn || !checkOut) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }
            
            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(guestEmail)) {
                e.preventDefault();
                alert('Please enter a valid email address');
                return false;
            }
            
            // Validate dates
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            
            if (checkOutDate <= checkInDate) {
                e.preventDefault();
                alert('Check-out date must be after check-in date');
                return false;
            }
            
            return true;
        });
    }
    
    // Update check-out min date when check-in changes
    const checkInInput = document.getElementById('check_in');
    if (checkInInput) {
        checkInInput.addEventListener('change', function() {
            const checkOutInput = document.getElementById('check_out');
            checkOutInput.setAttribute('min', this.value);
        });
    }
});

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
}