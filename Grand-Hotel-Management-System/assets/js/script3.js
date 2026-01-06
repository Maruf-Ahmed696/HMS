let events = [];

function loadEvents(){
    fetch("../ajax/event_list.php")
        .then(res => res.json())
        .then(data => {
            events = data;

            const select = document.getElementById("eventSelect");
            select.innerHTML = "";

            if(events.length === 0){
                select.innerHTML = "<option>No events available</option>";
                return;
            }

            events.forEach(e => {
                const opt = document.createElement("option");
                opt.value = e.id;
                opt.textContent = e.title;
                select.appendChild(opt);
            });

            loadEvent();
        });
}

function loadEvent(){
    const id = document.getElementById("eventSelect").value;
    const event = events.find(e => e.id == id);

    if(!event) return;

    document.getElementById("eventTitle").innerText = event.title;
    document.getElementById("eventDesc").innerText  = event.description ?? "";
    document.getElementById("totalSeats").innerText = event.total_seats;
    document.getElementById("availableSeats").innerText = event.available_seats;
}

function reserveSeats(){
    const email = document.getElementById("guestEmail").value.trim();
    const seats = document.getElementById("seatInput").value;

    if(email === "" || seats <= 0){
        document.getElementById("guestMsg").innerText =
            "Please enter valid email and seat number";
        return;
    }

    fetch("../ajax/reservation.php",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify({
            email: email,
            event: document.getElementById("eventSelect").value,
            seats: seats
        })
    })
    .then(res => res.json())
    .then(resp => {
        if(resp.status === "success"){
            document.getElementById("guestMsg").innerText =
                "Reservation successful";
            document.getElementById("seatInput").value = "";
            loadEvents();
        }else{
            document.getElementById("guestMsg").innerText =
                resp.msg || "Reservation failed";
        }
    });
}

loadEvents();
