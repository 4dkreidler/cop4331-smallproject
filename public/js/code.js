console.log("code.js loaded!");

document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");

    if (!loginForm) {
        console.error("loginForm not found! Check your form ID in index.html");
        return;
    }

    loginForm.addEventListener("submit", function (e) {
        e.preventDefault(); // prevent default form submission

        const login = document.getElementById("login").value.trim();
        const password = document.getElementById("password").value.trim();

        fetch("http://whateverwhateverwhatever.xyz/LAMPAPI/Login.php", {
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
                localStorage.setItem("userId", data.ID);
                localStorage.setItem("FirstName", data.FirstName);
                localStorage.setItem("LastName", data.LastName);

                // Debug: check current URL
                console.log("Current URL:", window.location.href);
                console.log("Redirecting to dashboard...");

                // Redirect to dashboard
                window.location.href = "dashboard.html";
            } else {
                showError(data.message || "Invalid login");
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            showError("Error connecting to server");
        });
    });

});
    
    registerButton.addEventListener("click", function() {
      const login = document.getElementById("login").value.trim();
      const password = document.getElementById("password").value.trim();
      const firstName = document.getElementById("firstName").value.trim();
      const lastName = document.getElementById("lastName").value.trim();
      
      if(!login || !password){
        showError("Please enter an username and password to register.");
        return;
      }
      
      if(!firstName || !lastName){
        showError("Please enter your first and last names to register.");
        return;
      }
      
      fetch("http://whateverwhateverwhatever.xyz/LAMPAPI/Register.php", {
            method: "POST",
            body: JSON.stringify({
                Login: login,
                Password: password,
                FirstName: firstName,
                LastName: lastName
            }),
            headers: { "Content-Type": "application/json" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Save user info for the dashboard
                showError("Registration successful.");
            } else {
                showError(data.message || "Registration failed.");
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
    

