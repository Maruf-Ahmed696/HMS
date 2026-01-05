function handleTaskSubmit(event) {
    event.preventDefault();


    var taskName = document.getElementById("taskName").value;
    var assignStaff = document.getElementById("assignStaff").value;
    var taskStatus = document.getElementById("taskStatus").value;
    var taskNotification = document.getElementById("taskNotification").checked;

    var valid = true;


    document.querySelectorAll('.error-message').forEach(e => e.innerText = "");
    document.getElementById("formError").style.display = "none";


    if (!taskName) {
        document.getElementById("errorTaskName").innerText = "Task Name is required!";
        valid = false;
    }


    if (!assignStaff) {
        document.getElementById("errorAssignStaff").innerText = "Please select a staff member!";
        valid = false;
    }

    if (!taskStatus) {
        document.getElementById("errorTaskStatus").innerText = "Please select a task status!";
        valid = false;
    }

    if (!valid) {
        document.getElementById("formError").style.display = "block";
        return false;
    }


    document.getElementById("confirmationTaskName").textContent = taskName;
    document.getElementById("confirmationAssignStaff").textContent = assignStaff;
    document.getElementById("confirmationTaskStatus").textContent = taskStatus;
    document.getElementById("confirmationNotification").textContent = taskNotification ? "Yes" : "No";

    document.getElementById("confirmation").style.display = "block";
}


document.getElementById("taskForm").addEventListener("submit", handleTaskSubmit);
