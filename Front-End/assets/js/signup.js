$(document).on("click", "#signupBtn", function(e) {
    e.preventDefault();

    const full_name = $("#fullname").val();
    const jmbg = $("#jmbg").val();
    const email = $("#signup_email").val();
    const password = $("#signup_password").val();

    fetch(`${BASE_URL}/users/register`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ full_name, jmbg, email, password })
    })
    .then(res => res.json())
    .then(res => {
        if (res.error) {
            toastr.error(res.error || "Signup failed");
            return;
        }

        toastr.success("Account created successfully!");
        window.location.hash = "#login";
    })
    .catch(err => {
        console.error("Signup error:", err);
        toastr.error("Server error");
    });
});
