initDemoAccounts();


function initDemoAccounts() {
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    
    const demoUsers = [
        {
            id: 'admin-001',
            name: 'Admin User',
            email: 'admin@hotel.com',
            username: 'admin',
            password: 'admin123',
            role: 'admin',
            status: 'active',
            createdAt: new Date('2024-01-01').toISOString(),
            loyaltyPoints: 0
        },
        {
            id: 'staff-001',
            name: 'Staff Member',
            email: 'staff@hotel.com',
            username: 'staff',
            password: 'staff123',
            role: 'staff',
            status: 'active',
            createdAt: new Date('2024-02-15').toISOString(),
            loyaltyPoints: 0
        },
        {
            id: 'guest-001',
            name: 'Guest User',
            email: 'guest@hotel.com',
            username: 'guest',
            password: 'guest123',
            role: 'guest',
            status: 'active',
            createdAt: new Date('2024-03-10').toISOString(),
            loyaltyPoints: 150
        }
    ];
    
    demoUsers.forEach(demoUser => {
        const exists = users.find(u => u.email === demoUser.email);
        if (!exists) {
            users.push(demoUser);
        }
    });
    
    localStorage.setItem('hotelUsers', JSON.stringify(users));
}

function generateId() {
    return 'user-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
}


function handleSignup(e) {
    e.preventDefault();
    
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const repassword = document.getElementById('repassword').value;
    const role = document.getElementById('role').value;
    
    let hasError = false;
    
    if (name.length < 2) {
        document.getElementById('nameError').textContent = 'Name must be at least 2 characters';
        hasError = true;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address';
        hasError = true;
    }
    
    if (username.length < 3) {
        document.getElementById('usernameError').textContent = 'Username must be at least 3 characters';
        hasError = true;
    }
    
    if (password.length < 8) {
        document.getElementById('passwordError').textContent = 'Password must be at least 8 characters';
        hasError = true;
    }
    
    if (password !== repassword) {
        document.getElementById('repasswordError').textContent = 'Passwords do not match';
        hasError = true;
    }
    
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    
    if (users.find(u => u.email === email)) {
        document.getElementById('emailError').textContent = 'This email is already registered';
        hasError = true;
    }
    
    if (users.find(u => u.username === username)) {
        document.getElementById('usernameError').textContent = 'This username is already taken';
        hasError = true;
    }
    
    if (hasError) return;
    
    const newUser = {
        id: generateId(),
        name,
        email,
        username,
        password,
        role,
        status: 'active',
        createdAt: new Date().toISOString(),
        loyaltyPoints: 0,
        profileCompleted: false
    };
    
    users.push(newUser);
    localStorage.setItem('hotelUsers', JSON.stringify(users));
    
    logActivity('signup', `New ${role} registered: ${name}`);
    
    localStorage.setItem('currentUser', JSON.stringify(newUser));
    
    window.location.href = 'profile.html';
}

function handleSignin(e) {
    e.preventDefault();
    
    const emailOrUsername = document.getElementById('emailOrUsername').value.trim();
    const password = document.getElementById('password').value;
    
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    
    const user = users.find(u => 
        (u.email === emailOrUsername || u.username === emailOrUsername) && 
        u.password === password
    );
    
    if (!user) {
        document.getElementById('loginError').textContent = 'Invalid email/username or password';
        return;
    }
    
    if (user.status === 'suspended') {
        document.getElementById('loginError').textContent = 'Your account has been suspended. Please contact admin.';
        return;
    }
    
    logActivity('login', `${user.name} logged in`);
    
    localStorage.setItem('currentUser', JSON.stringify(user));
    
    if (user.role === 'admin' || user.role === 'staff') {
        window.location.href = 'dashboard.html';
    } else {
        window.location.href = 'guest-dashboard.html';
    }
}

function logout() {
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (currentUser) {
        logActivity('logout', `${currentUser.name} logged out`);
    }
    localStorage.removeItem('currentUser');
    window.location.href = 'signin.html';
}

function checkAuth() {
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) {
        window.location.href = 'signin.html';
        return false;
    }
    
    const userNameElement = document.getElementById('userName');
    if (userNameElement) {
        userNameElement.textContent = currentUser.name;
    }
    
    return true;
}

function checkAdminAuth() {
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) {
        window.location.href = 'signin.html';
        return false;
    }
    
    if (currentUser.role !== 'admin' && currentUser.role !== 'staff') {
        window.location.href = 'guest-dashboard.html';
        return false;
    }
    
    const userNameElement = document.getElementById('userName');
    if (userNameElement) {
        userNameElement.textContent = currentUser.name;
    }
    
    return true;
}

function logActivity(type, message) {
    const activities = JSON.parse(localStorage.getItem('hotelActivities')) || [];
    
    activities.unshift({
        id: Date.now(),
        type,
        message,
        timestamp: new Date().toISOString()
    });
    
    if (activities.length > 100) {
        activities.pop();
    }
    
    localStorage.setItem('hotelActivities', JSON.stringify(activities));
}
