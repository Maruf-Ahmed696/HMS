document.getElementById('bookBtn').addEventListener('click', function () {
    document.querySelectorAll('.error').forEach(el => el.innerText = '');


    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('contact', document.getElementById('contact').value);
    formData.append('roomType', document.getElementById('roomType').value);
    formData.append('days', document.getElementById('days').value);
    formData.append('coupon', document.getElementById('coupon').value);

    fetch('../Ajax/process_booking.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                if (data.errors.name) document.getElementById('nameErr').innerText = data.errors.name;
                if (data.errors.contact) document.getElementById('contactErr').innerText = data.errors.contact;
                if (data.errors.roomType) document.getElementById('roomErr').innerText = data.errors.roomType;
                if (data.errors.days) document.getElementById('daysErr').innerText = data.errors.days;
            } else {
                alert(data.message);
                window.location.href = "payment.php";
            }
        })
        .catch(error => console.error('Error:', error));
});