class ValidationHelper {
    static isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    static isValidJMBG(jmbg) {
        const jmbgRegex = /^\d{13}$/;
        return jmbgRegex.test(jmbg);
    }

    static validatePassword(password) {
        if (!password || password.length < 6) {
            return {
                isValid: false,
                message: "Password must be at least 6 characters long"
            };
        }
        return { isValid: true, message: "" };
    }

    static validateRequired(value, fieldName) {
        if (!value || value.trim() === "") {
            return {
                isValid: false,
                message: `${fieldName} is required`
            };
        }
        return { isValid: true, message: "" };
    }

    static validateFullName(name) {
        if (!name || name.trim().length < 2) {
            return {
                isValid: false,
                message: "Full name must be at least 2 characters long"
            };
        }
        return { isValid: true, message: "" };
    }

    static validateLoginForm(email, password) {
        const errors = [];

        // Check required fields
        if (!email || email.trim() === "") {
            errors.push("Email is required");
        } else if (!this.isValidEmail(email)) {
            errors.push("Please enter a valid email address");
        }

        if (!password || password.trim() === "") {
            errors.push("Password is required");
        }

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    static validateSignupForm(formData) {
        const errors = [];

        // Validate full name
        const nameValidation = this.validateFullName(formData.full_name);
        if (!nameValidation.isValid) {
            errors.push(nameValidation.message);
        }

        // Validate JMBG
        if (!formData.jmbg || formData.jmbg.trim() === "") {
            errors.push("JMBG is required");
        } else if (!this.isValidJMBG(formData.jmbg)) {
            errors.push("JMBG must be exactly 13 digits");
        }

        // Validate email
        if (!formData.email || formData.email.trim() === "") {
            errors.push("Email is required");
        } else if (!this.isValidEmail(formData.email)) {
            errors.push("Please enter a valid email address");
        }

        // Validate password
        const passwordValidation = this.validatePassword(formData.password);
        if (!passwordValidation.isValid) {
            errors.push(passwordValidation.message);
        }

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    static validateContactForm(formData) {
        const errors = [];

        // Validate name
        if (!formData.name || formData.name.trim() === "") {
            errors.push("Name is required");
        }

        // Validate email
        if (!formData.email || formData.email.trim() === "") {
            errors.push("Email is required");
        } else if (!this.isValidEmail(formData.email)) {
            errors.push("Please enter a valid email address");
        }

        // Validate message
        if (!formData.message || formData.message.trim() === "") {
            errors.push("Message is required");
        } else if (formData.message.trim().length < 10) {
            errors.push("Message must be at least 10 characters long");
        }

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    static displayErrors(errors) {
        errors.forEach(error => {
            toastr.error(error);
        });
    }

    static addRealTimeValidation(inputId, validationFn) {
        const input = document.getElementById(inputId);
        if (!input) return;

        input.addEventListener('blur', function() {
            const value = this.value;
            const result = validationFn(value);
            
            if (!result.isValid) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                
                // Create or update error message
                let errorDiv = this.nextElementSibling;
                if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv = document.createElement('div');
                    errorDiv.classList.add('invalid-feedback');
                    this.parentNode.insertBefore(errorDiv, this.nextSibling);
                }
                errorDiv.textContent = result.message;
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                
                // Remove error message
                const errorDiv = this.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv.remove();
                }
            }
        });

        // Clear validation on focus
        input.addEventListener('focus', function() {
            this.classList.remove('is-invalid', 'is-valid');
        });
    }

    static sanitizeInput(input) {
        if (typeof input !== 'string') return input;
        
        const div = document.createElement('div');
        div.textContent = input;
        return div.innerHTML;
    }

    static validateCandidateSelection(candidateId) {
        if (!candidateId || candidateId === "" || candidateId === "0") {
            return {
                isValid: false,
                message: "Please select a candidate"
            };
        }
        return { isValid: true, message: "" };
    }
}

// Make available globally
window.ValidationHelper = ValidationHelper;