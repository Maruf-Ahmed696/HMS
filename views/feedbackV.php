<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Feedback and Reviews</title>
    <link rel="stylesheet" href="../Assets/css/feedbackCSS.css">
    <style>
        .error-message {
            color: #ff0000 !important;
            font-weight: bold;
            display: block;
            min-height: 15px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Leave Your Feedback</h2>
        <form id="reviewForm">
            <label>Rating:</label>
            <select id="rating">
                <option value="">--Select Rating--</option>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select>
            <span id="errorRating" class="error-message"></span>

            <label>Review:</label>
            <textarea id="review" rows="4" placeholder="Write your review here..."></textarea>
            <span id="errorReview" class="error-message"></span>

            <button type="button" id="submitReviewBtn">Submit Review</button>
        </form>
    </div>

    <script src="../Assets/js/feedback.js"></script>
</body>

</html>