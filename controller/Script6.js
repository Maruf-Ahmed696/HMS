let allUsers = [];
let filteredUsers = [];
let currentPage = 1;
const usersPerPage = 10;

function initUserManagement() {
    loadUsers();
    document.getElementById('editUserForm')
        .addEventListener('submit', handleEditUser);
}

function loadUsers() {
    allUsers = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    filteredUsers = [...allUsers];
    renderUserTable();
}

function renderUserTable() {
    const tbody = document.getElementById('userTableBody');
    const start = (currentPage - 1) * usersPerPage;
    const pageUsers = filteredUsers.slice(start, start + usersPerPage);

    if (pageUsers.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7">No users found</td></tr>`;
        return;
    }

    tbody.innerHTML = pageUsers.map(user => `
        <tr>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.username}</td>
            <td>${user.role}</td>
            <td>${user.status}</td>
            <td>${new Date(user.createdAt).toLocaleDateString()}</td>
            <td>
                <button class="btn edit" onclick="editUser('${user.id}')">Edit</button>
                ${user.status === 'active'
                    ? `<button class="btn suspend" onclick="confirmSuspend('${user.id}')">Suspend</button>`
                    : `<button class="btn activate" onclick="activateUser('${user.id}')">Activate</button>`
                }
                <button class="btn delete" onclick="confirmDelete('${user.id}')">Delete</button>
            </td>
        </tr>
    `).join('');

    updatePagination();
}

function updatePagination() {
    const totalPages = Math.ceil(filteredUsers.length / usersPerPage) || 1;
    document.getElementById('pageInfo').innerText =
        `Page ${currentPage} of ${totalPages}`;
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
    filterUsers();
}

function filterUsers() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;

    filteredUsers = allUsers.filter(u =>
        (u.name.toLowerCase().includes(search) ||
         u.email.toLowerCase().includes(search) ||
         u.username.toLowerCase().includes(search)) &&
        (role === 'all' || u.role === role) &&
        (status === 'all' || u.status === status)
    );

    currentPage = 1;
    renderUserTable();
}

function editUser(id) {
    const user = allUsers.find(u => u.id === id);
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

    const id = editUserId.value;
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    const index = users.findIndex(u => u.id === id);

    if (index !== -1) {
        users[index].name = editName.value;
        users[index].email = editEmail.value;
        users[index].role = editRole.value;

        localStorage.setItem('hotelUsers', JSON.stringify(users));
        loadUsers();
        closeModal();
    }
}

function confirmSuspend(id) {
    setConfirm(
        "Suspend User",
        "Are you sure you want to suspend this user?",
        () => suspendUser(id)
    );
}

function suspendUser(id) {
    updateStatus(id, 'suspended');
}

function activateUser(id) {
    updateStatus(id, 'active');
}

function confirmDelete(id) {
    setConfirm(
        "Delete User",
        "This action cannot be undone. Continue?",
        () => deleteUser(id)
    );
}

function deleteUser(id) {
    let users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    users = users.filter(u => u.id !== id);
    localStorage.setItem('hotelUsers', JSON.stringify(users));
    loadUsers();
    closeConfirmModal();
}

function updateStatus(id, status) {
    const users = JSON.parse(localStorage.getItem('hotelUsers')) || [];
    const u = users.find(x => x.id === id);
    if (u) {
        u.status = status;
        localStorage.setItem('hotelUsers', JSON.stringify(users));
        loadUsers();
    }
    closeConfirmModal();
}

function setConfirm(title, msg, action) {
    document.getElementById('confirmTitle').innerText = title;
    document.getElementById('confirmMessage').innerText = msg;
    document.getElementById('confirmAction').onclick = action;
    document.getElementById('confirmModal').classList.add('active');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('active');
}
