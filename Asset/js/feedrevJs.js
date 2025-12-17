function handleReviewSubmit(event) {
    event.preventDefault();


    var rating = document.getElementById("rating").value;
    var review = document.getElementById("review").value;


    document.querySelectorAll('.error-message').forEach(e => e.innerText = "");
    document.getElementById("formError").style.display = "none";

    var valid = true;


    if (!rating) {
        document.getElementById("errorRating").innerText = "Please select a rating!";
        valid = false;
    }


    if (!review) {
        document.getElementById("errorReview").innerText = "Please write a review!";
        valid = false;
    }


    if (!valid) {
        document.getElementById("formError").style.display = "block";
        return false;
    }


    document.getElementById("confirmRating").innerText = rating + " Star(s)";
    document.getElementById("confirmReview").innerText = review;

    document.getElementById("confirmation").style.display = "block";
    return false;
}


document.getElementById("reviewForm").addEventListener("submit", handleReviewSubmit);
