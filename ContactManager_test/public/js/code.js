console.log("code.js loaded!");

document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");

    if (!loginForm) {
        console.error("loginForm not found! Check your form ID in index.html");
        return;
    }

    loginForm.addEventListener("submit", function (e) {
        e.preventDefault(); // prevent default form submission

        const login = document.getElementById("login").value;
        const password = document.getElementById("password").value;

        fetch("../api/Login.php", {
            method: "POST",
            body: JSON.stringify({
                Login: login,
                Password: password
            }),
            headers: { "Content-Type": "application/json" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Save user info for the dashboard
                localStorage.setItem("FirstName", data.FirstName);
                localStorage.setItem("LastName", data.LastName);

                // Debug: check current URL
                console.log("Current URL:", window.location.href);
                console.log("Redirecting to dashboard...");

                // Redirect to dashboard
                window.location.href = "/ContactManager/public/dashboard.html";
            } else {
                showError(data.message || "Invalid login");
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            showError("Error connecting to server");
        });
    });

    // Function to display error messages below form
    function showError(message) {
        let errorDiv = document.getElementById("errorMessage");
        if (!errorDiv) {
            errorDiv = document.createElement("div");
            errorDiv.id = "errorMessage";
            errorDiv.style.color = "red";
            errorDiv.style.marginTop = "10px";
            loginForm.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }
});
