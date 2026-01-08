function addRequest() {
    let room = document.getElementById("room").value;
    let service = document.getElementById("service").value;
    let list = document.getElementById("list");

    if(room === "") {
        alert("Please enter room number!");
        return;
    }

    let formData = new FormData();
    formData.append("room", room);
    formData.append("service", service);

    
    fetch('../controllers/room_service.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);

        if(data.success) {
            
            let newItem = document.createElement("li");
            newItem.innerText = "Room " + room + ": " + service;
            list.appendChild(newItem);
            
            
            document.getElementById("room").value = "";
        }
    })
    .catch(error => console.error('Error:', error));
}