//alert Function
function showAlert(message, type = "success") {
    const container = document.getElementById("alertContainer");

    container.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
             <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}

//Get user detais from the index.html
const form = document.getElementById("new_user_form");

form.addEventListener("submit", async function (event) {

    //stop page from refreshing
    event.preventDefault();

    //get values enterd by the user
    const user = {
        name: document.getElementById("name").value.trim(),
        about: document.getElementById("about").value.trim(),
        birthday: document.getElementById("birthday").value,
        mobile: document.getElementById("mobile").value.trim(),
        email: document.getElementById("email").value.trim(),
        country: document.getElementById("country").value
    };

    try {
        //send user data to the API
        const response = await fetch("api/users.php", {
            method: "POST",
            headers: {"Content-Type": "application/json" },
            body: JSON.stringify(user)
        });
        
        //conver JSON response to the JS
        const result = await response.json();
        if (!response.ok) {
            showAlert(result.message,"success");
            return;
        }
        showAlert(result.message);

        //clear all the form details
        form.reset();

    } catch (error) {
        console.log(error);
        showAlert("Could not connect to the server");
    }
});