class UserService {
    constructor() {
        this.baseURL = "http://localhost/ElvirPandur/WEB-PROGRAMMING-PROJECT/Back-End";
    }

    getAuthHeaders() {
        const token = localStorage.getItem("jwt");
        if (!token) {
            toastr.error("You are not authorized. Please login.");
            window.location.href = "#login";
            return null;
        }
        return {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        };
    }

    async login(email, password) {
        try {
            const response = await fetch(`${this.baseURL}/users/login`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();
            
            if (!response.ok || !data.token) {
                throw new Error(data.error || "Invalid login");
            }

            return data;
        } catch (error) {
            console.error("Login error:", error);
            throw error;
        }
    }

    async register(userData) {
        try {
            const response = await fetch(`${this.baseURL}/users/register`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(userData)
            });

            const data = await response.json();

            if (!response.ok || data.error) {
                throw new Error(data.error || "Registration failed");
            }

            return data;
        } catch (error) {
            console.error("Registration error:", error);
            throw error;
        }
    }

    async getAllUsers() {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/users`, {
                headers: headers
            });

            if (!response.ok) {
                throw new Error("Failed to fetch users");
            }

            return await response.json();
        } catch (error) {
            console.error("Error fetching users:", error);
            throw error;
        }
    }

    async getUserById(userId) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/users/${userId}`, {
                headers: headers
            });

            if (!response.ok) {
                throw new Error("User not found");
            }

            return await response.json();
        } catch (error) {
            console.error("Error fetching user:", error);
            throw error;
        }
    }

    async updateUser(userId, userData) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/users/${userId}`, {
                method: "PUT",
                headers: headers,
                body: JSON.stringify(userData)
            });

            if (!response.ok) {
                throw new Error("Failed to update user");
            }

            return await response.json();
        } catch (error) {
            console.error("Error updating user:", error);
            throw error;
        }
    }

    async deleteUser(userId) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/users/${userId}`, {
                method: "DELETE",
                headers: headers
            });

            if (!response.ok) {
                throw new Error("Failed to delete user");
            }

            return await response.json();
        } catch (error) {
            console.error("Error deleting user:", error);
            throw error;
        }
    }

    async resetPassword(userId, newPassword) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/users/${userId}/reset-password`, {
                method: "PUT",
                headers: headers,
                body: JSON.stringify({ password: newPassword })
            });

            if (!response.ok) {
                throw new Error("Failed to reset password");
            }

            return await response.json();
        } catch (error) {
            console.error("Error resetting password:", error);
            throw error;
        }
    }

    logout() {
        localStorage.removeItem("jwt");
        localStorage.removeItem("user");
        window.location.hash = "#login";
    }

    getCurrentUser() {
        const userStr = localStorage.getItem("user");
        return userStr ? JSON.parse(userStr) : null;
    }

    isLoggedIn() {
        return !!localStorage.getItem("jwt");
    }

    isAdmin() {
        const user = this.getCurrentUser();
        return user && user.role === "admin";
    }
}

// Export singleton instance
const userService = new UserService();