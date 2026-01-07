<?php
session_start();

// Initialize errors and confirmation variables from session
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$confirmation = isset($_SESSION['confirmation']) ? $_SESSION['confirmation'] : null;

// Clear errors and confirmation after displaying them to avoid showing on subsequent page loads
unset($_SESSION['errors']);
unset($_SESSION['confirmation']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback and Reviews</title>
    <link rel="stylesheet" href="../Assets/css/feedbackCSS.css">
</head>

<body>
    <div class="container">
        <h2>Leave Your Feedback</h2>

        <form id="reviewForm" action="../controllers/feedbackC.php" method="POST">

            <!-- Rating -->
            <label for="rating">Rating:</label>
            <select id="rating" name="rating">
                <option value="">--Select Rating--</option>
                <option value="1" <?= (isset($_COOKIE['rating']) && $_COOKIE['rating'] == '1') ? 'selected' : '' ?>>1 Star
                </option>
                <option value="2" <?= (isset($_COOKIE['rating']) && $_COOKIE['rating'] == '2') ? 'selected' : '' ?>>2 Stars
                </option>
                <option value="3" <?= (isset($_COOKIE['rating']) && $_COOKIE['rating'] == '3') ? 'selected' : '' ?>>3 Stars
                </option>
                <option value="4" <?= (isset($_COOKIE['rating']) && $_COOKIE['rating'] == '4') ? 'selected' : '' ?>>4 Stars
                </option>
                <option value="5" <?= (isset($_COOKIE['rating']) && $_COOKIE['rating'] == '5') ? 'selected' : '' ?>>5 Stars
                </option>
            </select>
            <span id="errorRating" class="error-message">
                <?php if (isset($errors['rating'])): ?>
                    <?= $errors['rating']; ?>
                <?php endif; ?>
            </span><br><br>

            <!-- Review -->
            <label for="review">Review:</label><br>
            <textarea id="review" name="review" rows="4" cols="50"
                placeholder="Write your review here..."><?= isset($_COOKIE['review']) ? $_COOKIE['review'] : '' ?></textarea>
            <span id="errorReview" class="error-message">
                <?php if (isset($errors['review'])): ?>
                    <?= $errors['review']; ?>
                <?php endif; ?>
            </span><br><br>

            <!-- Submit Button -->
            <button type="submit">Submit Review</button>
            <p id="formError" style="color: red; display: none;">Please fill in all required fields.</p>
        </form>

        <!-- Confirmation Display -->
        <?php if ($confirmation): ?>
            <div id="confirmation" style="display: block;">
                <h3>Your Review Submitted</h3>
                <p>Rating: <span id="confirmRating"><?= $confirmation['rating'] ?> Star(s)</span></p>
                <p>Review: <span id="confirmReview"><?= $confirmation['review'] ?></span></p>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>