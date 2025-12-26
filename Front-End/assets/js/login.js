// LOGIN HANDLER FOR SPA
$(document).on("click", "#loginBtn", function(e) {
    e.preventDefault();

    const email = $("#email").val();
    const password = $("#password").val();

    fetch(`${BASE_URL}/users/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password })
    })
    .then(res => res.json())
    .then(res => {
        if (!res.token) {
            toastr.error(res.error || "Invalid login");
            return;
        }

        localStorage.setItem("jwt", res.token);
        localStorage.setItem("user", JSON.stringify(res.user));

        updateAuthUI();
        toastr.success("You have successfully logged in");

        if (res.user.role === "admin") {
            window.location.hash = "#admin-panel";
        } else {
            window.location.hash = "#home";
        }
    })
    .catch(err => {
        console.error("Login error:", err);
        toastr.error("Server error");
    });
});

// SPA route protection
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
        window.location.hash = "#home"; // ili neka druga javna stranica
        return false;
    }

    return true;
}

// Provjera route-a pri promjeni hash-a
$(window).on("hashchange", function() {
    const hash = window.location.hash;

    if (hash === "#admin-panel") {
        if (!protectRoute("admin")) return;  // ovdje se može učitati admin-panel template ako je admin
    }
});