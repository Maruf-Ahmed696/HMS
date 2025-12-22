let events = [];
let editIndex = -1;

const titleInput = document.getElementById("title");
const descInput = document.getElementById("desc");
const dateInput = document.getElementById("date");
const eventForm = document.getElementById("eventForm");
const eventList = document.getElementById("eventList");
const roleSelect = document.getElementById("roleSelect");

roleSelect.addEventListener("change", renderEvents);

function saveEvent(){
    const title = titleInput.value.trim();
    const desc = descInput.value.trim();
    const date = dateInput.value;

    if(!title || !date){
        alert("Event title and date are required");
        return;
    }

    if(editIndex === -1){
        events.push({title, desc, date});
        alert("Event created successfully");
    }else{
        events[editIndex] = {title, desc, date};
        editIndex = -1;
        alert("Event updated successfully");
    }

    titleInput.value = "";
    descInput.value = "";
    dateInput.value = "";

    renderEvents();
}

function renderEvents(){
    const role = roleSelect.value;
    eventForm.style.display = role === "admin" ? "block" : "none";
    eventList.innerHTML = "";

    if(events.length === 0){
        eventList.innerHTML = "<p>No upcoming events</p>";
        return;
    }

    events.forEach((e, i) => {
        const div = document.createElement("div");
        div.className = "event";

        div.innerHTML = `
            <strong>${e.title}</strong><br>
            <small>Date: ${e.date}</small>
            <p>${e.desc}</p>
        `;

        if(role === "admin"){
            const editBtn = document.createElement("button");
            editBtn.textContent = "Edit";
            editBtn.onclick = () => editEvent(i);

            const deleteBtn = document.createElement("button");
            deleteBtn.textContent = "Delete";
            deleteBtn.className = "delete-btn";
            deleteBtn.onclick = () => deleteEvent(i);

            div.appendChild(editBtn);
            div.appendChild(deleteBtn);
        }

        eventList.appendChild(div);
    });
}

function editEvent(i){
    const e = events[i];
    titleInput.value = e.title;
    descInput.value = e.desc;
    dateInput.value = e.date;
    editIndex = i;
}

function deleteEvent(i){
    if(confirm("Are you sure you want to delete this event?")){
        events.splice(i,1);
        renderEvents();
    }
}

renderEvents();
