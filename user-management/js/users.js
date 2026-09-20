//Load users
async function loadUsers(filters = {}) {
    try {
        const params = new URLSearchParams(filters);

        //Get user from the API
        const response = await fetch("api/users.php?" + params.toString());

        //convert resonse to JS
        const users = await response.json();
        const userList = document.getElementById("user_list");

        //check there is no users 
        if (users.length === 0) {
            userList.innerHTML = "<p class='text-center'>No users found.</p>";
            return;
        }

        //create table
        let table = `<div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>About You</th>
                            <th>Birthday</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Country</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        users.forEach(user => {

            table += `<tr id="user-row-${user.id}">
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.about_you}</td>
                    <td>${user.birthday}</td>
                    <td>${user.mobile_number}</td>
                    <td>${user.email}</td>
                    <td>${user.country}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editUser(${user.id})">
                            Edit
                        </button>
                    
                    </td>
                </tr>
            `;

        });

        table += `</tbody>
                </table>
            </div>
        `;

        userList.innerHTML = table;

    } catch (error) {
        console.log(error);
        document.getElementById("user_list").innerHTML =
            "<p class='text-danger'>Could not load users.</p>";
    }
}

// Show all users when the page first opens
loadUsers();

// Filter button
document.getElementById("filter_button").addEventListener("click", function () {

    const country = document.getElementById("country_filter").value;
    const from = document.getElementById("birthday_from").value;
    const to = document.getElementById("birthday_to").value;

    // Check whether From date is after To date
    if (from !== "" && to !== "" && from > to) {
        showAlert("Incorrect Date","warning");
        return;
    }

    loadUsers({
        country: country,
        from: from,
        to: to
    });

});

// Reset button
document.getElementById("reset_button").addEventListener("click", function () {

    document.getElementById("country_filter").value = "";
    document.getElementById("birthday_from").value = "";
    document.getElementById("birthday_to").value = "";

    loadUsers();
});

function editUser(id) {
    window.location.href = "edit.html?id=" + id;
}
