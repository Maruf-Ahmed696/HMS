let events = [];
let editId = null;

const titleInput = document.getElementById("title");
const descInput  = document.getElementById("desc");
const dateInput  = document.getElementById("date");
const eventForm  = document.getElementById("eventForm");
const eventList  = document.getElementById("eventList");
const roleSelect = document.getElementById("roleSelect");

roleSelect.addEventListener("change", renderEvents);

function loadEvents(){
    fetch("../ajax/event_list.php")
        .then(res => res.json())
        .then(data => {
            events = data;
            renderEvents();
        });
}

function saveEvent(){
    const title = titleInput.value.trim();
    const desc  = descInput.value.trim();
    const date  = dateInput.value;

    if(!title || !date){
        alert("Event title and date are required");
        return;
    }

    fetch("../ajax/event.php",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify({
            title: title,
            desc: desc,
            date: date,
            total: 100
        })
    })
    .then(res => res.json())
    .then(resp => {
        if(resp.status === "success"){
            alert("Event saved successfully");
            titleInput.value = "";
            descInput.value  = "";
            dateInput.value  = "";
            loadEvents();
        }else{
            alert("Failed to save event");
        }
    });
}

function renderEvents(){
    const role = roleSelect.value;
    eventForm.style.display = role === "admin" ? "block" : "none";
    eventList.innerHTML = "";

    if(events.length === 0){
        eventList.innerHTML = "<p>No upcoming events</p>";
        return;
    }

    events.forEach(e => {
        const div = document.createElement("div");
        div.className = "event";

        div.innerHTML = `
            <strong>${e.title}</strong><br>
            <small>Date: ${e.event_date}</small>
            <p>${e.description ?? ""}</p>
        `;

        eventList.appendChild(div);
    });
}

loadEvents();
