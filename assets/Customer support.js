function submitComplaint() {
    let name = document.getElementById("username").value;
    let issue = document.getElementById("issue").value;

    if(name === "" || issue === "") {
        alert("Please fill all fields!");
    } else {
        alert("Success! Your Ticket ID is: 555");
        document.getElementById("username").value = "";
        document.getElementById("issue").value = "";
    }
}

function checkStatus() {
    let id = document.getElementById("checkId").value;
    let result = document.getElementById("result");

    if (id == "101") {
        result.innerText = "Solved";
        result.style.color = "green";
    } 
    else if (id == "555") {
        result.innerText = "Processing";
        result.style.color = "blue";
    }
    else {
        result.innerText = "Not Found";
        result.style.color = "red";
    }
}