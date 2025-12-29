$(document).ready(function () {

    // Load Dashboard using services
    $(document).on("click", "#loadDashboard", function () {
        loadStats();
        loadUsers();
    });

    function loadStats() {
        Promise.all([
            userService.getAllUsers(),
            candidateService.getAllCandidates(),
            voteService.getAllVotes()
        ])
        .then(([users, candidates, votes]) => {
            $("#totalUsers").text(users.length);
            $("#totalCandidates").text(candidates.length);
            $("#totalVotes").text(votes.length);
            
            // Store data for detailed views
            window.statsData = {
                users: users,
                candidates: candidates,
                votes: votes
            };
        })
        .catch(err => {
            console.error("Error loading stats:", err);
            toastr.error("Failed to load statistics");
        });
    }

    $(document).on("click", "#totalVotes", function() {
        if (!window.statsData) {
            toastr.error("Please load dashboard first!");
            return;
        }

        const { candidates, votes } = window.statsData;
        
        // Count votes per candidate
        const voteCounts = {};
        candidates.forEach(c => {
            voteCounts[c.candidate_id] = {
                name: c.full_name,
                party: c.party_name || "N/A",
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

    $(document).on("click", "#totalUsers", function() {
        if (!window.statsData) {
            toastr.error("Please load dashboard first!");
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
        html += '<thead><tr><th>Name</th><th>Position</th></tr></thead>';
        html += '<tbody>';

        candidates.forEach(c => {
            html += `
                <tr>
                    <td>${c.full_name}</td>
                    <td>${c.position || "N/A"}</td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        html += '</div></div>';

        $("body").append(html);
    });

    $(document).on("click", ".close-modal, .vote-details-modal", function(e) {
        if (e.target === this) {
            $(".vote-details-modal").remove();
        }
    });

    function loadUsers() {
        userService.getAllUsers()
            .then(users => {
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
            })
            .catch(err => {
                console.error("Error loading users:", err);
                toastr.error("Failed to load users");
            });
    }

    $(document).on("click", ".resetPasswordBtn", function () {
        const userId = $(this).data("id");
        const newPassword = prompt("Enter new password:");
        
        if (!newPassword) return;

        // CLIENT-SIDE VALIDATION
        const validation = ValidationHelper.validatePassword(newPassword);
        if (!validation.isValid) {
            toastr.error(validation.message);
            return;
        }

        userService.resetPassword(userId, newPassword)
            .then(res => {
                toastr.success(res.message || "Password reset successful");
            })
            .catch(err => {
                toastr.error(err.message || "Failed to reset password");
            });
    });

    $(document).on("click", ".deleteUserBtn", function () {
        const userId = $(this).data("id");
        
        if (!confirm("Are you sure you want to delete this user?")) return;

        userService.deleteUser(userId)
            .then(res => {
                toastr.success("User deleted successfully");
                loadUsers(); // Reload list
            })
            .catch(err => {
                console.error("Error deleting user:", err);
                toastr.error("Failed to delete user");
            });
    });
});