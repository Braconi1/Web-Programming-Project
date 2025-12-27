$(document).on("click", "#signupBtn", function(e) {
    e.preventDefault();
    const formData = {
        full_name: $("#fullname").val().trim(),
        jmbg: $("#jmbg").val().trim(),
        email: $("#signup_email").val().trim(),
        password: $("#signup_password").val()
    };

    const validation = ValidationHelper.validateSignupForm(formData);
    
    if (!validation.isValid) {
        ValidationHelper.displayErrors(validation.errors);
        return;
    }

    // Use UserService for registration
    userService.register(formData)
        .then(res => {
            toastr.success("Account created successfully!");
            
            // Clear form
            $("#fullname").val("");
            $("#jmbg").val("");
            $("#signup_email").val("");
            $("#signup_password").val("");
            
            // Redirect to login
            setTimeout(() => {
                window.location.hash = "#login";
            }, 1000);
        })
        .catch(err => {
            toastr.error(err.message || "Registration failed");
        });
});

$(document).ready(function() {
    setTimeout(function() {
        if ($("#fullname").length) {
            ValidationHelper.addRealTimeValidation("fullname", (value) => {
                return ValidationHelper.validateFullName(value);
            });
        }

        // JMBG validation
        if ($("#jmbg").length) {
            ValidationHelper.addRealTimeValidation("jmbg", (value) => {
                if (!value || value.trim() === "") {
                    return { isValid: false, message: "JMBG is required" };
                }
                if (!ValidationHelper.isValidJMBG(value)) {
                    return { isValid: false, message: "JMBG must be exactly 13 digits" };
                }
                return { isValid: true, message: "" };
            });
        }

        // Email validation
        if ($("#signup_email").length) {
            ValidationHelper.addRealTimeValidation("signup_email", (value) => {
                if (!value || value.trim() === "") {
                    return { isValid: false, message: "Email is required" };
                }
                if (!ValidationHelper.isValidEmail(value)) {
                    return { isValid: false, message: "Invalid email format" };
                }
                return { isValid: true, message: "" };
            });
        }

        // Password validation
        if ($("#signup_password").length) {
            ValidationHelper.addRealTimeValidation("signup_password", (value) => {
                return ValidationHelper.validatePassword(value);
            });
        }
    }, 500);
});

$(document).on("input", "#jmbg", function() {
    // Remove non-numeric characters
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Limit to 13 digits
    if (this.value.length > 13) {
        this.value = this.value.slice(0, 13);
    }
});