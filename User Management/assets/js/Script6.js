let users = [];

function initUserManagement(){
    loadUsers();

    document.getElementById("editUserForm")
        .addEventListener("submit", saveUser);
}

function loadUsers(){
    fetch("../ajax/user.php",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify({ action: "list" })
    })
    .then(res => res.json())
    .then(data => {
        users = data;
        renderUsers();
    });
}

function renderUsers(){
    const tbody = document.getElementById("userTableBody");
    tbody.innerHTML = "";

    if(users.length === 0){
        tbody.innerHTML = "<tr><td colspan='7'>No users found</td></tr>";
        return;
    }

    users.forEach(u => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>${u.name}</td>
            <td>${u.email}</td>
            <td>${u.username ?? ""}</td>
            <td>${u.role}</td>
            <td>${u.status}</td>
            <td>${u.created_at}</td>
            <td>
                <button onclick="editUser(${u.id})">Edit</button>
                ${
                    u.status === "active"
                    ? `<button onclick="changeStatus(${u.id},'suspended')">Suspend</button>`
                    : `<button onclick="changeStatus(${u.id},'active')">Activate</button>`
                }
                <button onclick="deleteUser(${u.id})">Delete</button>
            </td>
        `;

        tbody.appendChild(tr);
    });
}

function editUser(id){
    const user = users.find(u => u.id == id);
    if(!user) return;

    document.getElementById("editUserId").value = user.id;
    document.getElementById("editName").value = user.name;
    document.getElementById("editEmail").value = user.email;
    document.getElementById("editRole").value = user.role;

    document.getElementById("editModal").style.display = "flex";
}

function saveUser(e){
    e.preventDefault();

    fetch("../ajax/user.php",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify({
            action: "update",
            id: editUserId.value,
            name: editName.value,
            email: editEmail.value,
            role: editRole.value
        })
    })
    .then(res => res.json())
    .then(() => {
        closeModal();
        loadUsers();
    });
}

function changeStatus(id,status){
    fetch("../ajax/user.php",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify({
            action:"status",
            id:id,
            status:status
        })
    }).then(loadUsers);
}

function deleteUser(id){
    if(!confirm("Are you sure?")) return;

    fetch("../ajax/user.php",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify({
            action:"delete",
            id:id
        })
    }).then(loadUsers);
}


function closeModal(){
    document.getElementById("editModal").style.display = "none";
}
