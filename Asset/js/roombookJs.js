function handleSubmit(event) {
    event.preventDefault();


    let customerName = document.getElementById("customerName").value;
    let email = document.getElementById("email").value;
    let roomType = document.getElementById("roomType").value;
    let bookingDays = document.getElementById("bookingDays").value;
    let discountCode = document.getElementById("discountCode").value;

    var valid = true;


    document.querySelectorAll('.error-message').forEach(e => e.innerText = "");
    document.querySelectorAll('.input-field').forEach(e => e.style.border = "");


    if (!customerName) {
        document.getElementById("errorCustomerName").innerText = "Please enter your name.";
        document.getElementById("customerName").style.border = "2px solid red";
        valid = false;
    }


    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!email || !emailPattern.test(email)) {
        document.getElementById("errorEmail").innerText = "Please enter a valid email address.";
        document.getElementById("email").style.border = "2px solid red";
        valid = false;
    }


    if (!roomType) {
        document.getElementById("errorRoomType").innerText = "Please select a room type.";
        document.getElementById("roomType").style.border = "2px solid red";
        valid = false;
    }


    if (!bookingDays) {
        document.getElementById("errorBookingDays").innerText = "Please enter the number of days.";
        document.getElementById("bookingDays").style.border = "2px solid red";
        valid = false;
    }

    if (!valid) {
        return false;
    }


    let price = 0;
    if (roomType === "single") {
        price = 1000;
    } else if (roomType === "double") {
        price = 1500;
    } else if (roomType === "suite") {
        price = 2500;
    }


    let discountedPrice = price;
    if (discountCode === "DISCOUNT10") {
        discountedPrice = discountedPrice * 0.9;
    }


    const totalPrice = discountedPrice * bookingDays;


    const bookingID = "BOOK" + Math.floor(Math.random() * 10000);


    document.getElementById("bookingID").textContent = bookingID;
    document.getElementById("confirmationName").textContent = customerName;
    document.getElementById("confirmationDays").textContent = bookingDays;
    document.getElementById("confirmationRoomType").textContent = roomType.charAt(0).toUpperCase() + roomType.slice(1);
    document.getElementById("confirmationOriginalPrice").textContent = price * bookingDays;
    document.getElementById("confirmationPrice").textContent = totalPrice;

    document.getElementById("confirmation").style.display = "block";

    return false;
}


document.getElementById("bookingForm").addEventListener("submit", handleSubmit);
