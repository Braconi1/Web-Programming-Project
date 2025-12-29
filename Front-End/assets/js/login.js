$(document).on("click", "#loginBtn", function(e) {
    e.preventDefault();

    const email = $("#email").val().trim();
    const password = $("#password").val();

    // CLIENT-SIDE VALIDATION
    const validation = ValidationHelper.validateLoginForm(email, password);
    
    if (!validation.isValid) {
        ValidationHelper.displayErrors(validation.errors);
        return;
    }

    // Use UserService for login
    userService.login(email, password)
        .then(res => {
            // Store JWT and user data
            localStorage.setItem("jwt", res.token);
            localStorage.setItem("user", JSON.stringify(res.user));

            updateAuthUI();
            toastr.success("You have successfully logged in");

            // Redirect based on role
            if (res.user.role === "admin") {
                window.location.hash = "#admin-panel";
            } else {
                window.location.hash = "#home";
            }
        })
        .catch(err => {
            toastr.error(err.message || "Login failed");
        });
});

function protectRoute(requiredRole) {
    const user = JSON.parse(localStorage.getItem("user"));
    const token = localStorage.getItem("jwt");

    if (!token || !user) {
        toastr.error("You must login first!");
        window.location.hash = "#login";
        return false;
    }

    if (requiredRole && user.role !== requiredRole) {
        toastr.error("Access denied");
        window.location.hash = "#home";
        return false;
    }

    return true;
}

// Route protection on hash change
$(window).on("hashchange", function() {
    const hash = window.location.hash;

    if (hash === "#admin-panel") {
        if (!protectRoute("admin")) return;
    }
});

$(document).ready(function() {
    setTimeout(function() {
        if ($("#email").length) {
            ValidationHelper.addRealTimeValidation("email", (value) => {
                if (!value || value.trim() === "") {
                    return { isValid: false, message: "Email is required" };
                }
                if (!ValidationHelper.isValidEmail(value)) {
                    return { isValid: false, message: "Invalid email format" };
                }
                return { isValid: true, message: "" };
            });

            ValidationHelper.addRealTimeValidation("password", (value) => {
                if (!value || value.trim() === "") {
                    return { isValid: false, message: "Password is required" };
                }
                return { isValid: true, message: "" };
            });
        }
    }, 500);
});