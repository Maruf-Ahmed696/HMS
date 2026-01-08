// Open add modal
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Room';
    document.getElementById('roomForm').action = '../controllers/RoomController.php?action=create';
    document.getElementById('roomForm').reset();
    document.getElementById('roomId').value = '';
    document.getElementById('roomModal').classList.add('active');
}

// Edit room
function editRoom(room) {
    document.getElementById('modalTitle').textContent = 'Edit Room';
    document.getElementById('roomForm').action = '../controllers/RoomController.php?action=update';
    
    document.getElementById('roomId').value = room.id;
    document.getElementById('room_number').value = room.room_number;
    document.getElementById('room_type').value = room.room_type;
    document.getElementById('price').value = room.price;
    document.getElementById('status').value = room.status;
    document.getElementById('description').value = room.description;
    
    document.getElementById('roomModal').classList.add('active');
}

// Close modal
function closeModal() {
    document.getElementById('roomModal').classList.remove('active');
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const roomForm = document.getElementById('roomForm');
    
    if (roomForm) {
        roomForm.addEventListener('submit', function(e) {
            const roomNumber = document.getElementById('room_number').value.trim();
            const price = parseFloat(document.getElementById('price').value);
            
            if (roomNumber === '') {
                e.preventDefault();
                alert('Room number is required');
                return false;
            }
            
            if (price <= 0) {
                e.preventDefault();
                alert('Price must be greater than 0');
                return false;
            }
            
            return true;
        });
    }
});


window.onclick = function(event) {
    const modal = document.getElementById('roomModal');
    if (event.target == modal) {
        closeModal();
    }
}