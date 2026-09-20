//alert Function
function showAlert(message, type = "success") {
    const container = document.getElementById("alertContainer");

    container.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}

const params = new URLSearchParams(window.location.search);

const userId = params.get("id");

//Load the existing user.
async function loadUser() {
    try {
        //get values from JSON
        const response = await fetch("api/users.php?id="+ userId);
        const user = await response.json();

        if (!response.ok) {
            showAlert(user.message);
            return;
        }
        //replace new data with existing data
        document.getElementById("name").value = user.name;
        document.getElementById("about").value = user.about_you;
        document.getElementById("birthday").value = user.birthday;
        document.getElementById("mobile").value = user.mobile_number;
        document.getElementById("email").value = user.email;
        document.getElementById("country").value = user.country;

    } catch (error) {
        console.log(error);
        showAlert("Could not load user");
    }
}

loadUser();

//send the updated informations
const form = document.getElementById("edit_user_form");

form.addEventListener("submit", async function (event) {
    event.preventDefault();

    const user = {
        name: document.getElementById("name").value.trim(),
        about: document.getElementById("about").value.trim(),
        birthday: document.getElementById("birthday").value,
        mobile: document.getElementById("mobile").value.trim(),
        email: document.getElementById("email").value.trim(),
        country: document.getElementById("country").value
    };

    //send updated data to the API
    try {
        const response = await fetch("api/users.php?id=" + userId, {
            method: "PUT",
            headers: {"Content-Type": "application/json" },
            body: JSON.stringify(user)
        });
        const result = await response.json();

        if (!response.ok) {
            showAlert(result.message);
            return;
        }

        showAlert(result.message);
        window.location.href = "users.html";

    } catch (error) {
        console.log(error);
        showAlert("Could not update user","warning");

    }

});