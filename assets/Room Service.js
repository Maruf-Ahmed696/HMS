function addRequest() {
    let room = document.getElementById("room").value;
    let service = document.getElementById("service").value;

    if(room === "") {
        alert("Please enter room number!");
        return;
    }

    let list = document.getElementById("list");
    list.innerHTML += "<li>Room " + room + ": " + service + " (Pending)</li>";

    document.getElementById("room").value = "";
    alert("Success!");
}