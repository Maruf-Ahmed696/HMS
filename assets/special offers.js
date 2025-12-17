function getOffer(offerName) {
    alert("Congratulations! You selected: " + offerName);
}

var countDate = new Date("Dec 31, 2025 00:00:00").getTime();

var x = setInterval(function() {
    var now = new Date().getTime();
    var gap = countDate - now;

    var days = Math.floor(gap / (1000 * 60 * 60 * 24));
    var hours = Math.floor((gap % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((gap % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((gap % (1000 * 60)) / 1000);

    document.getElementById("countdown").innerText = 
        days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

}, 1000);