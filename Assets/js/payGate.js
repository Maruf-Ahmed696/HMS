document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.error').forEach(el => el.innerText = '');

        const fields = document.getElementById('dynamicFields');
        fields.style.display = 'block';

        const method = this.value;
        const ownerInput = document.getElementById('owner');
        const numberInput = document.getElementById('number');
        const securityInput = document.getElementById('securityCode');

        if (method === 'Visa') {
            ownerInput.placeholder = "Card Owner Name";
            numberInput.placeholder = "Card Number";
            securityInput.placeholder = "CVC";
        } else {
            ownerInput.placeholder = "Number Owner Name";
            numberInput.placeholder = "Account Number";
            securityInput.placeholder = "PIN";
        }
    });
});

document.getElementById('payBtn').addEventListener('click', function () {
    document.querySelectorAll('.error').forEach(el => el.innerText = '');

    const selected = document.querySelector('input[name="payment_method"]:checked');
    const formData = new FormData();

    formData.append('payment_method', selected ? selected.value : '');
    formData.append('owner', document.getElementById('owner').value);
    formData.append('number', document.getElementById('number').value);
    formData.append('amount', document.getElementById('amount').value);
    formData.append('securityCode', document.getElementById('securityCode').value);

    fetch('../Ajax/process_payment.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                if (data.errors.method) document.getElementById('methodErr').innerText = data.errors.method;
                if (data.errors.owner) document.getElementById('ownerErr').innerText = data.errors.owner;
                if (data.errors.number) document.getElementById('numberErr').innerText = data.errors.number;
                if (data.errors.amount) document.getElementById('amountErr').innerText = data.errors.amount;
                if (data.errors.security) document.getElementById('securityErr').innerText = data.errors.security;
            } else {
                alert(data.message);
                location.reload();
            }
        });
});