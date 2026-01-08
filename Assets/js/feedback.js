document.getElementById('submitReviewBtn').addEventListener('click', function () {
    document.querySelectorAll('.error-message').forEach(el => el.innerText = '');

    const formData = new FormData();
    formData.append('rating', document.getElementById('rating').value);
    formData.append('review', document.getElementById('review').value);

    fetch('../Ajax/process_feedback.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                if (data.errors.rating) document.getElementById('errorRating').innerText = data.errors.rating;
                if (data.errors.review) document.getElementById('errorReview').innerText = data.errors.review;
            } else {
                alert(data.message);
                location.reload();
            }
        });
});