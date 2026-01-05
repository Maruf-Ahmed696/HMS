function handleShiftSubmit(event) {
    event.preventDefault();


    var shiftType = document.getElementById("shiftType").value;
    var startTime = document.getElementById("startTime").value;
    var endTime = document.getElementById("endTime").value;
    var shiftNotification = document.getElementById("shiftNotification").checked;

    var valid = true;


    document.querySelectorAll('.error-message').forEach(e => e.innerText = "");
    document.getElementById("formError").style.display = "none";


    if (!shiftType) {
        document.getElementById("errorShiftType").innerText = "Please select a shift type!";
        valid = false;
    }


    if (!startTime) {
        document.getElementById("errorStartTime").innerText = "Please select a start time!";
        valid = false;
    }


    if (!endTime) {
        document.getElementById("errorEndTime").innerText = "Please select an end time!";
        valid = false;
    }

    if (!valid) {
        document.getElementById("formError").style.display = "block";
        return false;
    }


    document.getElementById("confirmationShiftType").textContent = shiftType;
    document.getElementById("confirmationStartTime").textContent = startTime;
    document.getElementById("confirmationEndTime").textContent = endTime;
    document.getElementById("confirmationNotification").textContent = shiftNotification ? "Yes" : "No";

    document.getElementById("confirmation").style.display = "block";
}


document.getElementById("shiftForm").addEventListener("submit", handleShiftSubmit);
