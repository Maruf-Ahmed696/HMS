const notifications = [];
const list = document.getElementById("notificationList");
const adminPanel = document.getElementById("adminPanel");
const systemToggle = document.getElementById("systemToggle");

let systemAlertInterval;

// Role switch
document.querySelectorAll("input[name='role']").forEach(radio=>{
    radio.addEventListener("change", ()=>{
        adminPanel.style.display =
            radio.value === "admin" && radio.checked ? "block" : "none";
    });
});

// Send admin notification
function sendNotification(){
    const type = document.getElementById("type").value;
    const msg = document.getElementById("message").value;

    if(msg.trim()===""){
        alert("Message required!");
        return;
    }

    notifications.unshift({
        type:type,
        message:msg,
        time:new Date().toLocaleString()
    });

    document.getElementById("message").value="";
    renderNotifications();
    resetSystemAlert();
}

// Render notification list
function renderNotifications(){
    list.innerHTML="";

    notifications.forEach(n=>{
        const div = document.createElement("div");
        div.className = `notification ${getClass(n.type)}`;
        div.innerHTML = `
            <strong>${n.type}</strong><br>
            ${n.message}
            <div class="time">${n.time}</div>
        `;
        list.appendChild(div);
    });
}

// Type → CSS class
function getClass(type){
    if(type.includes("Booking")) return "booking";
    if(type.includes("Payment")) return "payment";
    if(type.includes("Offer")) return "offer";
    if(type.includes("System")) return "system";
    return "general";
}

// Start system alert
function startSystemAlert(){
    systemAlertInterval = setInterval(()=>{
        if(!systemToggle.checked) return;

        notifications.unshift({
            type:"System Alert",
            message:"Don't forget to check latest updates!",
            time:new Date().toLocaleString()
        });
        renderNotifications();
    },30000);
}

// Reset timer after admin message
function resetSystemAlert(){
    clearInterval(systemAlertInterval);
    startSystemAlert();
}

// Toggle ON / OFF
systemToggle.addEventListener("change", ()=>{
    if(systemToggle.checked){
        systemToggle.nextSibling.textContent = " System Alert ON";
        startSystemAlert();
    }else{
        systemToggle.nextSibling.textContent = " System Alert OFF";
        clearInterval(systemAlertInterval);
    }
});

// Initial start
startSystemAlert();
