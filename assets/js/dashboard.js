let userGrowthChart = null;
let featureUsageChart = null;
let roleDistributionChart = null;

function initDashboard() {
    loadStatistics();
    initCharts();
    loadRecentActivity();
}

function loadStatistics() {
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    const bookings = JSON.parse(localStorage.getItem('hotelBookings')) || [];
    
    const totalUsers = users.length;
    const activeUsers = users.filter(u => u.status === 'active').length;
    const totalBookings = bookings.length;
    
    const totalRevenue = bookings.reduce((sum, b) => sum + (b.amount || 0), 0);
    
    document.getElementById('totalUsers').textContent = totalUsers;
    document.getElementById('activeUsers').textContent = activeUsers;
    document.getElementById('totalBookings').textContent = totalBookings;
    document.getElementById('totalRevenue').textContent = '$' + totalRevenue.toLocaleString();
}

function initCharts() {
    initUserGrowthChart();
    initFeatureUsageChart();
    initRoleDistributionChart();
}

function initUserGrowthChart() {
    const ctx = document.getElementById('userGrowthChart').getContext('2d');
    
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const currentMonth = new Date().getMonth();
    const currentYear = new Date().getFullYear();
    const displayMonths = [];
    const userData = [];
    
    for (let i = 5; i >= 0; i--) {
        const monthIndex = (currentMonth - i + 12) % 12;
        const year = currentMonth - i < 0 ? currentYear - 1 : currentYear;
        displayMonths.push(months[monthIndex]);
        
        const usersInMonth = users.filter(u => {
            const createdDate = new Date(u.createdAt);
            return createdDate.getMonth() === monthIndex && createdDate.getFullYear() === year;
        }).length;
        
        userData.push(usersInMonth);
    }
    
    userGrowthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: displayMonths,
            datasets: [{
                label: 'New Users',
                data: userData,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function initFeatureUsageChart() {
    const ctx = document.getElementById('featureUsageChart').getContext('2d');
    
    const activities = JSON.parse(localStorage.getItem('hotelActivities')) || [];
    
    const featureCounts = {
        'Login': activities.filter(a => a.type === 'login').length,
        'Signup': activities.filter(a => a.type === 'signup').length,
        'Profile': activities.filter(a => a.type === 'profile').length,
        'Reports': activities.filter(a => a.type === 'report').length,
        'Users': activities.filter(a => a.type === 'user_management' || a.type === 'user_edit' || a.type === 'user_delete' || a.type === 'user_suspend').length
    };
    
    featureUsageChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(featureCounts),
            datasets: [{
                label: 'Usage Count',
                data: Object.values(featureCounts),
                backgroundColor: [
                    '#3498db',
                    '#2ecc71',
                    '#9b59b6',
                    '#f39c12',
                    '#e74c3c'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function initRoleDistributionChart() {
    const ctx = document.getElementById('roleDistributionChart').getContext('2d');
    
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    
    const roleCounts = {
        'Admin': users.filter(u => u.role === 'admin').length,
        'Staff': users.filter(u => u.role === 'staff').length,
        'Guest': users.filter(u => u.role === 'guest').length
    };
    
    roleDistributionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(roleCounts),
            datasets: [{
                data: Object.values(roleCounts),
                backgroundColor: [
                    '#9b59b6',
                    '#3498db',
                    '#2ecc71'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function loadRecentActivity() {
    const activities = JSON.parse(localStorage.getItem('hotelActivities')) || [];
    const recentActivities = activities.slice(0, 10);
    
    const activityList = document.getElementById('recentActivityList');
    
    if (recentActivities.length === 0) {
        activityList.innerHTML = '<li>No recent activity</li>';
        return;
    }
    
    activityList.innerHTML = recentActivities.map(activity => {
        const icon = getActivityIcon(activity.type);
        const timeAgo = getTimeAgo(activity.timestamp);
        return `
            <li>
                <span>
                    <span class="activity-icon">${icon}</span>
                    ${activity.message}
                </span>
                <span class="activity-time">${timeAgo}</span>
            </li>
        `;
    }).join('');
}

function getActivityIcon(type) {
    const icons = {
        'login': '🔐',
        'logout': '🚪',
        'signup': '👤',
        'profile': '📝',
        'user_edit': '✏️',
        'user_suspend': '⚠️',
        'user_delete': '🗑️',
        'user_activate': '✅',
        'report': '📊',
        'booking': '🛏️'
    };
    return icons[type] || '📌';
}

function getTimeAgo(timestamp) {
    const now = new Date();
    const then = new Date(timestamp);
    const diffMs = now - then;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins}m ago`;
    if (diffHours < 24) return `${diffHours}h ago`;
    if (diffDays < 7) return `${diffDays}d ago`;
    return then.toLocaleDateString();
}
