document.getElementById('assignBtn').addEventListener('click', function () {
    document.querySelectorAll('.error-message').forEach(el => el.innerText = '');

    const formData = new FormData();
    formData.append('staffName', document.getElementById('staffName').value);
    formData.append('shiftType', document.getElementById('shiftType').value);
    formData.append('startTime', document.getElementById('startTime').value);
    formData.append('endTime', document.getElementById('endTime').value);
    formData.append('shiftNotification', document.getElementById('shiftNotification').checked ? 'on' : 'off');

    fetch('../Ajax/process_staff.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                if (data.errors.staffName) document.getElementById('errorStaffName').innerText = data.errors.staffName;
                if (data.errors.shiftType) document.getElementById('errorShiftType').innerText = data.errors.shiftType;
                if (data.errors.startTime) document.getElementById('errorStartTime').innerText = data.errors.startTime;
                if (data.errors.endTime) document.getElementById('errorEndTime').innerText = data.errors.endTime;
            } else {
                alert(data.message);
                location.reload();
            }
        });
});