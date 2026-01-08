
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (username === '' || password === '') {
                e.preventDefault();
                alert('Please fill in all fields');
                return false;
            }
            
            if (username.length < 3) {
                e.preventDefault();
                alert('Username must be at least 3 characters');
                return false;
            }
            
            if (containsHTMLTags(username)) {
                e.preventDefault();
                alert('Invalid characters in username');
                return false;
            }
            
            return true;
        });
    }

    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;

            if (username.length < 3) {
                e.preventDefault();
                alert('Username must be at least 3 characters');
                return false;
            }

            if (!validateEmail(email)) {
                e.preventDefault();
                alert('Please enter a valid email');
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters');
                return false;
            }

            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Passwords do not match');
                return false;
            }

            return true;
        });
    }
});

// Check for HTML tags (basic XSS prevention)
function containsHTMLTags(str) {
    const htmlPattern = /<[^>]*>/g;
    return htmlPattern.test(str);
}

// Validate email format
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate phone number
function validatePhone(phone) {
    const re = /^[\d\s\-\+\(\)]+$/;
    return re.test(phone);
}

// Sanitize input
function sanitizeInput(input) {
    const div = document.createElement('div');
    div.textContent = input;
    return div.innerHTML;
}