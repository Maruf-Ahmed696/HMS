let reportChart = null;
let currentReportData = [];

function initReports() {
    setDefaultDates();
    setupEventListeners();
}

function setDefaultDates() {
    const today = new Date();
    const thirtyDaysAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
    
    document.getElementById('dateTo').value = today.toISOString().split('T')[0];
    document.getElementById('dateFrom').value = thirtyDaysAgo.toISOString().split('T')[0];
}

function setupEventListeners() {
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        generateReport();
    });
}

function generateReport() {
    const reportType = document.getElementById('reportType').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    logActivity('report', `Generated ${reportType} report`);
    
    switch(reportType) {
        case 'users':
            generateUserReport(dateFrom, dateTo);
            break;
        case 'bookings':
            generateBookingReport(dateFrom, dateTo);
            break;
        case 'revenue':
            generateRevenueReport(dateFrom, dateTo);
            break;
        case 'activity':
            generateActivityReport(dateFrom, dateTo);
            break;
    }
    
    document.getElementById('reportResults').style.display = 'block';
}

function generateUserReport(dateFrom, dateTo) {
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    
    const filteredUsers = users.filter(u => {
        const createdDate = new Date(u.createdAt);
        return createdDate >= new Date(dateFrom) && createdDate <= new Date(dateTo);
    });
    
    const totalUsers = filteredUsers.length;
    const activeUsers = filteredUsers.filter(u => u.status === 'active').length;
    const newUsers = filteredUsers.length;
    
    document.getElementById('reportTitle').textContent = 'User Report';
    document.getElementById('reportDateRange').textContent = `${dateFrom} to ${dateTo}`;
    
    document.getElementById('summaryLabel1').textContent = 'Total Users';
    document.getElementById('summaryValue1').textContent = totalUsers;
    document.getElementById('summaryLabel2').textContent = 'Active Users';
    document.getElementById('summaryValue2').textContent = activeUsers;
    document.getElementById('summaryLabel3').textContent = 'New Signups';
    document.getElementById('summaryValue3').textContent = newUsers;
    
    currentReportData = filteredUsers.map(u => ({
        Name: u.name,
        Email: u.email,
        Role: u.role,
        Status: u.status,
        'Join Date': new Date(u.createdAt).toLocaleDateString()
    }));
    
    renderReportTable(['Name', 'Email', 'Role', 'Status', 'Join Date'], currentReportData);
    
    renderUserChart(users);
}

function generateBookingReport(dateFrom, dateTo) {
    const bookings = JSON.parse(localStorage.getItem('hotelBookings')) || [];
    
    const sampleBookings = [
        { id: 'BK001', guestName: 'John Doe', room: 'Deluxe Suite', checkIn: '2024-12-01', checkOut: '2024-12-05', amount: 800, status: 'Completed' },
        { id: 'BK002', guestName: 'Jane Smith', room: 'Standard Room', checkIn: '2024-12-03', checkOut: '2024-12-06', amount: 450, status: 'Completed' },
        { id: 'BK003', guestName: 'Bob Wilson', room: 'Executive Suite', checkIn: '2024-12-10', checkOut: '2024-12-12', amount: 600, status: 'Confirmed' },
        { id: 'BK004', guestName: 'Alice Brown', room: 'Family Room', checkIn: '2024-12-15', checkOut: '2024-12-20', amount: 1200, status: 'Pending' }
    ];
    
    const allBookings = bookings.length > 0 ? bookings : sampleBookings;
    
    const totalBookings = allBookings.length;
    const totalRevenue = allBookings.reduce((sum, b) => sum + b.amount, 0);
    const avgBookingValue = totalBookings > 0 ? Math.round(totalRevenue / totalBookings) : 0;
    
    document.getElementById('reportTitle').textContent = 'Booking Report';
    document.getElementById('reportDateRange').textContent = `${dateFrom} to ${dateTo}`;
    
    document.getElementById('summaryLabel1').textContent = 'Total Bookings';
    document.getElementById('summaryValue1').textContent = totalBookings;
    document.getElementById('summaryLabel2').textContent = 'Total Revenue';
    document.getElementById('summaryValue2').textContent = '$' + totalRevenue.toLocaleString();
    document.getElementById('summaryLabel3').textContent = 'Avg. Value';
    document.getElementById('summaryValue3').textContent = '$' + avgBookingValue;
    
    currentReportData = allBookings.map(b => ({
        'Booking ID': b.id,
        'Guest': b.guestName,
        'Room': b.room,
        'Check In': b.checkIn,
        'Check Out': b.checkOut,
        'Amount': '$' + b.amount,
        'Status': b.status
    }));
    
    renderReportTable(['Booking ID', 'Guest', 'Room', 'Check In', 'Check Out', 'Amount', 'Status'], currentReportData);
    
    renderBookingChart(allBookings);
}

function generateRevenueReport(dateFrom, dateTo) {
    const bookings = JSON.parse(localStorage.getItem('hotelBookings')) || [];
    
    const monthlyRevenue = {
        'Jan': 15000,
        'Feb': 18000,
        'Mar': 22000,
        'Apr': 20000,
        'May': 25000,
        'Jun': 30000
    };
    
    const totalRevenue = Object.values(monthlyRevenue).reduce((a, b) => a + b, 0);
    const avgMonthlyRevenue = Math.round(totalRevenue / Object.keys(monthlyRevenue).length);
    const peakRevenue = Math.max(...Object.values(monthlyRevenue));
    
    document.getElementById('reportTitle').textContent = 'Revenue Report';
    document.getElementById('reportDateRange').textContent = `${dateFrom} to ${dateTo}`;
    
    document.getElementById('summaryLabel1').textContent = 'Total Revenue';
    document.getElementById('summaryValue1').textContent = '$' + totalRevenue.toLocaleString();
    document.getElementById('summaryLabel2').textContent = 'Monthly Avg';
    document.getElementById('summaryValue2').textContent = '$' + avgMonthlyRevenue.toLocaleString();
    document.getElementById('summaryLabel3').textContent = 'Peak Month';
    document.getElementById('summaryValue3').textContent = '$' + peakRevenue.toLocaleString();
    
    currentReportData = Object.entries(monthlyRevenue).map(([month, revenue]) => ({
        'Month': month,
        'Revenue': '$' + revenue.toLocaleString(),
        'Bookings': Math.floor(revenue / 500),
        'Growth': Math.floor(Math.random() * 20 - 5) + '%'
    }));
    
    renderReportTable(['Month', 'Revenue', 'Bookings', 'Growth'], currentReportData);
    
    renderRevenueChart(monthlyRevenue);
}

function generateActivityReport(dateFrom, dateTo) {
    const activities = JSON.parse(localStorage.getItem('hotelActivities')) || [];
    
    const filteredActivities = activities.filter(a => {
        const activityDate = new Date(a.timestamp);
        return activityDate >= new Date(dateFrom) && activityDate <= new Date(dateTo);
    });
    
    const displayActivities = filteredActivities.length > 0 ? filteredActivities : activities.slice(0, 20);
    
    const totalActivities = displayActivities.length;
    const loginCount = displayActivities.filter(a => a.type === 'login').length;
    const signupCount = displayActivities.filter(a => a.type === 'signup').length;
    
    document.getElementById('reportTitle').textContent = 'Activity Report';
    document.getElementById('reportDateRange').textContent = `${dateFrom} to ${dateTo}`;
    
    document.getElementById('summaryLabel1').textContent = 'Total Activities';
    document.getElementById('summaryValue1').textContent = totalActivities;
    document.getElementById('summaryLabel2').textContent = 'Logins';
    document.getElementById('summaryValue2').textContent = loginCount;
    document.getElementById('summaryLabel3').textContent = 'Signups';
    document.getElementById('summaryValue3').textContent = signupCount;
    
    currentReportData = displayActivities.map(a => ({
        'Type': a.type,
        'Description': a.message,
        'Date': new Date(a.timestamp).toLocaleDateString(),
        'Time': new Date(a.timestamp).toLocaleTimeString()
    }));
    
    renderReportTable(['Type', 'Description', 'Date', 'Time'], currentReportData);
    
    renderActivityChart(displayActivities);
}

function renderReportTable(headers, data) {
    const thead = document.getElementById('reportTableHead');
    const tbody = document.getElementById('reportTableBody');
    
    thead.innerHTML = `<tr>${headers.map(h => `<th>${h}</th>`).join('')}</tr>`;
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="' + headers.length + '" style="text-align: center;">No data available</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(row => 
        `<tr>${headers.map(h => `<td>${row[h] || '-'}</td>`).join('')}</tr>`
    ).join('');
}

function renderUserChart(users) {
    const ctx = document.getElementById('reportChart').getContext('2d');
    
    if (reportChart) {
        reportChart.destroy();
    }
    
    const roleCounts = {
        'Admin': users.filter(u => u.role === 'admin').length,
        'Staff': users.filter(u => u.role === 'staff').length,
        'Guest': users.filter(u => u.role === 'guest').length
    };
    
    reportChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(roleCounts),
            datasets: [{
                data: Object.values(roleCounts),
                backgroundColor: ['#9b59b6', '#3498db', '#2ecc71']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Users by Role'
                }
            }
        }
    });
}

function renderBookingChart(bookings) {
    const ctx = document.getElementById('reportChart').getContext('2d');
    
    if (reportChart) {
        reportChart.destroy();
    }
    
    const statusCounts = {};
    bookings.forEach(b => {
        statusCounts[b.status] = (statusCounts[b.status] || 0) + 1;
    });
    
    reportChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(statusCounts),
            datasets: [{
                label: 'Bookings by Status',
                data: Object.values(statusCounts),
                backgroundColor: ['#27ae60', '#3498db', '#f39c12', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function renderRevenueChart(monthlyRevenue) {
    const ctx = document.getElementById('reportChart').getContext('2d');
    
    if (reportChart) {
        reportChart.destroy();
    }
    
    reportChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: Object.keys(monthlyRevenue),
            datasets: [{
                label: 'Monthly Revenue',
                data: Object.values(monthlyRevenue),
                borderColor: '#27ae60',
                backgroundColor: 'rgba(39, 174, 96, 0.1)',
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
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

function renderActivityChart(activities) {
    const ctx = document.getElementById('reportChart').getContext('2d');
    
    if (reportChart) {
        reportChart.destroy();
    }
    
    const typeCounts = {};
    activities.forEach(a => {
        typeCounts[a.type] = (typeCounts[a.type] || 0) + 1;
    });
    
    reportChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(typeCounts),
            datasets: [{
                data: Object.values(typeCounts),
                backgroundColor: ['#3498db', '#2ecc71', '#9b59b6', '#f39c12', '#e74c3c', '#1abc9c']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Activity Types'
                }
            }
        }
    });
}

function generateQuickReport(type) {
    const today = new Date();
    let dateFrom, dateTo;
    
    dateTo = today.toISOString().split('T')[0];
    
    switch(type) {
        case 'daily':
            dateFrom = dateTo;
            document.getElementById('reportType').value = 'activity';
            break;
        case 'weekly':
            dateFrom = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            document.getElementById('reportType').value = 'bookings';
            break;
        case 'monthly':
            dateFrom = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
            document.getElementById('reportType').value = 'revenue';
            break;
        case 'users':
            dateFrom = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            document.getElementById('reportType').value = 'users';
            break;
    }
    
    document.getElementById('dateFrom').value = dateFrom;
    document.getElementById('dateTo').value = dateTo;
    
    generateReport();
}

function exportCSV() {
    if (currentReportData.length === 0) {
        alert('Please generate a report first');
        return;
    }
    
    const headers = Object.keys(currentReportData[0]);
    const csvContent = [
        headers.join(','),
        ...currentReportData.map(row => 
            headers.map(h => `"${row[h] || ''}"`).join(',')
        )
    ].join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', `report_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
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
