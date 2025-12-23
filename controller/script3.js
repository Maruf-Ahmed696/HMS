let events = [
    {
        title: "Business Conference",
        desc: "Annual conference with experts.",
        total: 100,
        available: 100
    },
    {
        title: "Wedding Ceremony",
        desc: "Luxury wedding arrangement.",
        total: 100,
        available: 100
    },
    {
        title: "Music Night",
        desc: "Live music & dinner night.",
        total: 80,
        available: 80
    }
];

let currentEvent = 0;

function init(){
    const select = document.getElementById("eventSelect");
    select.innerHTML = "";
    events.forEach((e, i)=>{
        let opt = document.createElement("option");
        opt.value = i;
        opt.text = e.title;
        select.appendChild(opt);
    });
    loadEvent();
}

function loadEvent(){
    currentEvent = document.getElementById("eventSelect").value;
    const e = events[currentEvent];

    document.getElementById("eventTitle").innerText = e.title;
    document.getElementById("eventDesc").innerText = e.desc;
    document.getElementById("totalSeats").innerText = e.total;
    document.getElementById("availableSeats").innerText = e.available;
}

function switchRole(role){
    document.getElementById("guestPanel").classList.add("hidden");
    document.getElementById("adminPanel").classList.add("hidden");

    if(role === "guest"){
        document.getElementById("guestPanel").classList.remove("hidden");
    }else{
        document.getElementById("adminPanel").classList.remove("hidden");
    }
}

function reserveSeats(){
    const seats = Number(document.getElementById("seatInput").value);
    const email = document.getElementById("guestEmail").value;
    const msg = document.getElementById("guestMsg");
    const e = events[currentEvent];

    msg.innerText = "";
    msg.className = "";

    if(!email || seats <= 0){
        msg.innerText = "Invalid input!";
        msg.className = "error";
        return;
    }

    if(seats > e.available){
        msg.innerText = "Not enough seats!";
        msg.className = "error";
        return;
    }

    e.available -= seats;
    loadEvent();

    msg.innerText = "Reservation confirmed. Email sent!";
    msg.className = "success";
}

function updateSeats(){
    const newTotal = Number(document.getElementById("adminSeatInput").value);
    const msg = document.getElementById("adminMsg");
    const e = events[currentEvent];

    msg.innerText = "";
    msg.className = "";

    if(newTotal <= 0){
        msg.innerText = "Invalid seat number!";
        msg.className = "error";
        return;
    }

    e.total = newTotal;
    if(e.available > newTotal){
        e.available = newTotal;
    }

    loadEvent();
    msg.innerText = "Seat capacity updated!";
    msg.className = "success";
}

init();
