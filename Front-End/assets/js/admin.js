//Admin-Panel logika
$(document).ready(function () {

    // Jwt TOKEN HELPER
    function getAuthHeaders() {
        const token = localStorage.getItem("jwt");
        if (!token) {
            toastr.error("You are not authorized. Please login.");
            window.location.href = "login.html"; 
            return null;
        }
        return {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        };
    }

    // Load Dashboard 
    $(document).on("click", "#loadDashboard", function () {
        loadStats();
        loadUsers();
    });

    // Load Stats 
    function loadStats() {
        const headers = getAuthHeaders();
        if (!headers) return;

        $.when(
            $.ajax({
                url: 'http://localhost/ElvirPandur/WEB-PROGRAMMING-PROJECT/Back-End/users',
                headers: headers
            }),
            $.ajax({
                url: 'http://localhost/ElvirPandur/WEB-PROGRAMMING-PROJECT/Back-End/candidates',
                headers: headers
            }),
            $.ajax({
                url: 'http://localhost/ElvirPandur/WEB-PROGRAMMING-PROJECT/Back-End/votes',
                headers: headers
            })
        ).done(function (users, candidates, votes) {
            $("#totalUsers").text(users[0].length);
            $("#totalCandidates").text(candidates[0].length);
            $("#totalVotes").text(votes[0].length);
            
            // Store data for detailed views
            window.statsData = {
                users: users[0],
                candidates: candidates[0],
                votes: votes[0]
            };
        }).fail(function (err) {
            console.error("Error loading stats:", err);
        });
    }

    // Show Vote Details 
    $(document).on("click", "#totalVotes", function() {
        if (!window.statsData) {
            alert("Please load dashboard first!");
            return;
        }

        const { candidates, votes } = window.statsData;
        
        // Count votes per candidate
        const voteCounts = {};
        candidates.forEach(c => {
            voteCounts[c.candidate_id] = {
                name: c.full_name,
                party: c.party_name,
                count: 0
            };
        });

        votes.forEach(v => {
            if (voteCounts[v.candidate_id]) {
                voteCounts[v.candidate_id].count++;
            }
        });

        // Sort by vote count
        const sorted = Object.entries(voteCounts)
            .sort((a, b) => b[1].count - a[1].count);

        // Build HTML
        let html = '<div class="vote-details-modal">';
        html += '<div class="modal-content">';
        html += '<span class="close-modal">&times;</span>';
        html += '<h3>Vote Statistics by Candidate</h3>';
        html += '<table class="vote-table">';
        html += '<thead><tr><th>Rank</th><th>Candidate</th><th>Party</th><th>Votes</th><th>%</th></tr></thead>';
        html += '<tbody>';

        const totalVotes = votes.length;
        sorted.forEach(([id, data], index) => {
            let percentage = 0;

        if (totalVotes > 0) {
            percentage = (data.count / totalVotes) * 100;
            percentage = percentage.toFixed(1);
        }

        html += `
            <tr>
                    <td>${index + 1}</td>
                    <td>${data.name}</td>
                    <td>${data.party}</td>
                    <td>${data.count}</td>
                    <td>${percentage}%</td>
            </tr>
            `;
        });

        html += '</tbody></table>';
        html += '</div></div>';

        $("body").append(html);
    });

    // Show User Details 
    $(document).on("click", "#totalUsers", function() {
        if (!window.statsData) {
            alert("Please load dashboard first!");
            return;
        }

        const { users } = window.statsData;
        
        let html = '<div class="vote-details-modal">';
        html += '<div class="modal-content">';
        html += '<span class="close-modal">&times;</span>';
        html += '<h3>User Statistics</h3>';
        html += '<table class="vote-table">';
        html += '<thead><tr><th>Name</th><th>Email</th><th>Role</th></tr></thead>';
        html += '<tbody>';

        users.forEach(user => {
            html += `
                <tr>
                    <td>${user.full_name}</td>
                    <td>${user.email}</td>
                    <td><span class="role-badge ${user.role}">${user.role}</span></td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        html += '</div></div>';

        $("body").append(html);
    });

    // Show Candidate Details 
    $(document).on("click", "#totalCandidates", function() {
        if (!window.statsData) {
            toastr.error("Please load dashboard first!");
            return;
        }

        const { candidates } = window.statsData;
        
        let html = '<div class="vote-details-modal">';
        html += '<div class="modal-content">';
        html += '<span class="close-modal">&times;</span>';
        html += '<h3>Candidate List</h3>';
        html += '<table class="vote-table">';
        html += '<thead><tr><th>Name</th></tr></thead>';
        html += '<tbody>';

        candidates.forEach(c => {
            html += `
                <tr>
                    <td>${c.full_name}</td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        html += '</div></div>';

        $("body").append(html);
    });

    // Close Modal 
    $(document).on("click", ".close-modal, .vote-details-modal", function(e) {
        if (e.target === this) {
            $(".vote-details-modal").remove();
        }
    });

    // Load Users 
    function loadUsers() {
        const headers = getAuthHeaders();
        if (!headers) return;

        $.ajax({
            url: 'http://localhost/ElvirPandur/WEB-PROGRAMMING-PROJECT/Back-End/users',
            headers: headers,
            success: function (users) {
                $("#usersList").empty();
                users.forEach(function (user) {
                    const userCard = $(`
                        <div class="user-card">
                            <p><strong>${user.full_name}</strong> (${user.email})</p>
                            <p>Role: ${user.role}</p>
                            <button class="resetPasswordBtn" data-id="${user.user_id}">Reset Password</button>
                            <button class="deleteUserBtn" data-id="${user.user_id}">Delete User</button>
                        </div>
                    `);
                    $("#usersList").append(userCard);
                });
            },
            error: function (err) {
                toastr.error("Error loading users:", err);
            }
        });
    }

    // Reset Password 
    $(document).on("click", ".resetPasswordBtn", function () {
        const userId = $(this).data("id");
        const newPassword = prompt("Enter new password:");
        if (!newPassword) return;

        const headers = getAuthHeaders();
        if (!headers) return;

        $.ajax({
            url: `http://localhost/ElvirPandur/WEB-PROGRAMMING-PROJECT/Back-End/users/${userId}/reset-password`,
            type: "PUT",
            headers: headers,
            data: JSON.stringify({ password: newPassword }),
            success: function (res) {
                toastr.success(res.message || "Password reset successful");
            },
            error: function (err) {
                toastr.error("Error resetting password:", err);
            }
        });
    });

    // Delete User 
    $(document).on("click", ".deleteUserBtn", function () {
        const userId = $(this).data("id");
        
        if (!confirm("Are you sure you want to delete this user?")) return;

        const headers = getAuthHeaders();
        if (!headers) return;

        $.ajax({
            url: `http://localhost/ElvirPandur/WEB-PROGRAMMING-PROJECT/Back-End/users/${userId}`,
            type: "DELETE",
            headers: headers,
            success: function (res) {
                toastr.success("User deleted successfully");
                loadUsers(); // Reload list
            },
            error: function (err) {
                console.error("Error deleting user:", err);
            }
        });
    });
});