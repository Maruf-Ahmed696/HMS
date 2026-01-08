document.getElementById('addRoomBtn').addEventListener('click', function () {
    document.querySelectorAll('.error-message').forEach(el => el.innerText = '');

    const formData = new FormData();
    formData.append('roomType', document.getElementById('roomType').value);
    formData.append('roomNumber', document.getElementById('roomNumber').value);
    formData.append('roomAvailability', document.getElementById('roomAvailability').value);
    formData.append('roomStatus', document.getElementById('roomStatus').value);

    fetch('../Ajax/process_room.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                if (data.errors.roomType) document.getElementById('errorRoomType').innerText = data.errors.roomType;
                if (data.errors.roomNumber) document.getElementById('errorRoomNumber').innerText = data.errors.roomNumber;
                if (data.errors.roomAvailability) document.getElementById('errorRoomAvailability').innerText = data.errors.roomAvailability;
                if (data.errors.roomStatus) document.getElementById('errorRoomStatus').innerText = data.errors.roomStatus;
            } else {
                alert(data.message);
                location.reload();
            }
        });
});