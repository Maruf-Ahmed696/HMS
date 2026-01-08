function sendMsg() {
    let name = document.getElementById("userName").value;
    let msg = document.getElementById("userMsg").value;

    let formData = new FormData();
    formData.append("userName", name);
    formData.append("userMsg", msg);

    
    fetch('../controllers/submit_ticket.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if(data.success) {
            document.getElementById("userName").value = "";
            document.getElementById("userMsg").value = "";
        }
    });
}

function trackTicket() {
    let id = document.getElementById("ticketId").value;
    let resultBox = document.getElementById("statusResult");

    let formData = new FormData();
    formData.append("ticketId", id);

    fetch('../controllers/check_ticket.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        resultBox.innerText = data.message;
        resultBox.style.color = data.color;
    });
}