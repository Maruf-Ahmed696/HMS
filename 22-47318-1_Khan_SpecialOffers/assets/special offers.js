function getOffer(offerName) {
    console.log("Sending request for: " + offerName);

    let formData = new FormData();
    formData.append("offer_name", offerName);

    fetch('../controllers/claim_offer.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message); 
        } else {
            alert("Failed: " + data.message); 
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Server Error! Check Console.");
    });
}

var countDate = new Date("Dec 31, 2026 00:00:00").getTime();

var x = setInterval(function() {
    var now = new Date().getTime();
    var gap = countDate - now;

    var days = Math.floor(gap / (1000 * 60 * 60 * 24));
    var hours = Math.floor((gap % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((gap % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((gap % (1000 * 60)) / 1000);

    if (gap < 0) {
        clearInterval(x);
        document.getElementById("countdown").innerText = "OFFER EXPIRED";
    } else {
        document.getElementById("countdown").innerText = 
            days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
    }
}, 1000);