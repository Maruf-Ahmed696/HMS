let allUsers = [];
let filteredUsers = [];
let currentPage = 1;
const usersPerPage = 10;

function initUserManagement() {
    loadUsers();
    setupEventListeners();
}

function loadUsers() {
    allUsers = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    filteredUsers = [...allUsers];
    renderUserTable();
}

function setupEventListeners() {
    document.getElementById('editUserForm').addEventListener('submit', handleEditUser);
}

function renderUserTable() {
    const tbody = document.getElementById('userTableBody');
    const lang = localStorage.getItem('hotelLanguage') || 'en';
    const t = translations[lang] || translations['en'];
    
    const startIndex = (currentPage - 1) * usersPerPage;
    const endIndex = startIndex + usersPerPage;
    const pageUsers = filteredUsers.slice(startIndex, endIndex);
    
    const noUsersText = {
        en: 'No users found',
        es: 'No se encontraron usuarios',
        fr: 'Aucun utilisateur trouvé'
    };
    
    const editText = { en: 'Edit', es: 'Editar', fr: 'Modifier' };
    const suspendText = { en: 'Suspend', es: 'Suspender', fr: 'Suspendre' };
    const activateText = { en: 'Activate', es: 'Activar', fr: 'Activer' };
    const deleteText = { en: 'Delete', es: 'Eliminar', fr: 'Supprimer' };
    
    if (pageUsers.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align: center;">${noUsersText[lang] || noUsersText['en']}</td></tr>`;
        return;
    }
    
    tbody.innerHTML = pageUsers.map(user => `
        <tr>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.username}</td>
            <td><span class="role-badge role-${user.role}">${t['role_' + user.role] || user.role}</span></td>
            <td><span class="status-badge status-${user.status}">${t['status_' + user.status] || user.status}</span></td>
            <td>${new Date(user.createdAt).toLocaleDateString()}</td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn edit" onclick="editUser('${user.id}')">${editText[lang] || editText['en']}</button>
                    ${user.status === 'active' 
                        ? `<button class="action-btn suspend" onclick="confirmSuspend('${user.id}')">${suspendText[lang] || suspendText['en']}</button>`
                        : `<button class="action-btn activate" onclick="activateUser('${user.id}')">${activateText[lang] || activateText['en']}</button>`
                    }
                    <button class="action-btn delete" onclick="confirmDelete('${user.id}')">${deleteText[lang] || deleteText['en']}</button>
                </div>
            </td>
        </tr>
    `).join('');
    
    updatePagination();
}

function updatePagination() {
    const totalPages = Math.ceil(filteredUsers.length / usersPerPage);
    document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages || 1}`;
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderUserTable();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredUsers.length / usersPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderUserTable();
    }
}

function searchUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    filterUsers();
}

function filterUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    filteredUsers = allUsers.filter(user => {
        const matchesSearch = user.name.toLowerCase().includes(searchTerm) ||
                            user.email.toLowerCase().includes(searchTerm) ||
                            user.username.toLowerCase().includes(searchTerm);
        
        const matchesRole = roleFilter === 'all' || user.role === roleFilter;
        const matchesStatus = statusFilter === 'all' || user.status === statusFilter;
        
        return matchesSearch && matchesRole && matchesStatus;
    });
    
    currentPage = 1;
    renderUserTable();
}

function editUser(userId) {
    const user = allUsers.find(u => u.id === userId);
    if (!user) return;
    
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editName').value = user.name;
    document.getElementById('editEmail').value = user.email;
    document.getElementById('editRole').value = user.role;
    
    document.getElementById('editModal').classList.add('active');
}

function closeModal() {
    document.getElementById('editModal').classList.remove('active');
}

function handleEditUser(e) {
    e.preventDefault();
    
    const userId = document.getElementById('editUserId').value;
    const newName = document.getElementById('editName').value;
    const newEmail = document.getElementById('editEmail').value;
    const newRole = document.getElementById('editRole').value;
    
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    const userIndex = users.findIndex(u => u.id === userId);
    
    if (userIndex !== -1) {
        users[userIndex].name = newName;
        users[userIndex].email = newEmail;
        users[userIndex].role = newRole;
        
        localStorage.setItem('hotelUsers', JSON.stringify(users));
        
        logActivity('user_edit', `User ${newName} was edited`);
        
        loadUsers();
        closeModal();
    }
}

function confirmSuspend(userId) {
    const user = allUsers.find(u => u.id === userId);
    if (!user) return;
    
    document.getElementById('confirmTitle').textContent = 'Suspend User';
    document.getElementById('confirmMessage').textContent = `Are you sure you want to suspend ${user.name}? They will not be able to log in.`;
    
    document.getElementById('confirmAction').onclick = () => suspendUser(userId);
    document.getElementById('confirmModal').classList.add('active');
}

function suspendUser(userId) {
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    const userIndex = users.findIndex(u => u.id === userId);
    
    if (userIndex !== -1) {
        users[userIndex].status = 'suspended';
        localStorage.setItem('hotelUsers', JSON.stringify(users));
        
        logActivity('user_suspend', `User ${users[userIndex].name} was suspended`);
        
        loadUsers();
        closeConfirmModal();
    }
}

function activateUser(userId) {
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    const userIndex = users.findIndex(u => u.id === userId);
    
    if (userIndex !== -1) {
        users[userIndex].status = 'active';
        localStorage.setItem('hotelUsers', JSON.stringify(users));
        
        logActivity('user_activate', `User ${users[userIndex].name} was activated`);
        
        loadUsers();
    }
}

function confirmDelete(userId) {
    const user = allUsers.find(u => u.id === userId);
    if (!user) return;
    
    document.getElementById('confirmTitle').textContent = 'Delete User';
    document.getElementById('confirmMessage').textContent = `Are you sure you want to permanently delete ${user.name}? This action cannot be undone.`;
    
    document.getElementById('confirmAction').onclick = () => deleteUser(userId);
    document.getElementById('confirmModal').classList.add('active');
}

function deleteUser(userId) {
    let users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    const user = users.find(u => u.id === userId);
    
    if (user) {
        logActivity('user_delete', `User ${user.name} was deleted`);
        
        users = users.filter(u => u.id !== userId);
        localStorage.setItem('hotelUsers', JSON.stringify(users));
        
        loadUsers();
        closeConfirmModal();
    }
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('active');
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