function handleRoomManagementSubmit(event) {
    event.preventDefault();


    var roomType = document.getElementById("roomType").value;
    var roomNumber = document.getElementById("roomNumber").value;
    var roomAvailability = document.getElementById("roomAvailability").value;
    var roomStatus = document.getElementById("roomStatus").value;

    var valid = true;


    document.querySelectorAll('.error-message').forEach(e => e.innerText = "");


    if (!roomType) {
        document.getElementById("errorRoomType").innerText = "Room Type is required!";
        valid = false;
    }


    if (!roomNumber || roomNumber <= 0) {
        document.getElementById("errorRoomNumber").innerText = "Room Number is required and must be a positive number!";
        valid = false;
    }


    if (!roomAvailability) {
        document.getElementById("errorRoomAvailability").innerText = "Room Availability is required!";
        valid = false;
    }


    if (!roomStatus) {
        document.getElementById("errorRoomStatus").innerText = "Room Status is required!";
        valid = false;
    }

    if (!valid) {
        return false;
    }

    document.getElementById("confirmationRoomType").textContent = roomType;
    document.getElementById("confirmationRoomNumber").textContent = roomNumber;
    document.getElementById("confirmationRoomAvailability").textContent = roomAvailability;
    document.getElementById("confirmationRoomStatus").textContent = roomStatus;

    document.getElementById("confirmation").style.display = "block";
    return false;
}


document.getElementById("roomManagementForm").addEventListener("submit", handleRoomManagementSubmit);
