function loadNotifications(){
    fetch("../ajax/notification.php",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(data => {
        const list = document.getElementById("notificationList");
        list.innerHTML = "";

        if(data.length === 0){
            list.innerHTML = "<p>No notifications available</p>";
            return;
        }

        data.forEach(n => {
            const div = document.createElement("div");
            div.className = "notification";

            div.innerHTML = `
                <strong>${n.type}</strong>
                <p>${n.message}</p>
                <div class="time">${n.created_at}</div>
            `;

            list.appendChild(div);
        });
    });
}

function sendNotification(){
    const type = document.getElementById("type").value;
    const message = document.getElementById("message").value.trim();

    if(message === ""){
        alert("Message cannot be empty");
        return;
    }

    fetch("../ajax/notification.php",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify({
            action: "send",
            type: type,
            message: message
        })
    })
    .then(res => res.json())
    .then(resp => {
        if(resp.status === "success"){
            document.getElementById("message").value = "";
            loadNotifications();
        }else{
            alert("Failed to send notification");
        }
    });
}

loadNotifications();
