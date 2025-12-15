// Base URL ka backendu
const BASE_URL = "http://localhost/ElvirPandur/WEB-PROGRAMMING-PROJECT/Back-End";


// DOM UCITAVANJE FUKCIJA
document.addEventListener("DOMContentLoaded", () => {
    loadParties();
});

function loadParties() {
    fetch(`${BASE_URL}/parties`)
        .then(res => res.json())
        .then(data => {
            const grid = document.getElementById("partyGrid");
            grid.innerHTML = "";
            data.forEach(party => {
                const card = document.createElement("div");
                card.className = "party-card";
                card.innerHTML = `
                    <img src="${BASE_URL}/uploads/${party.logo}" class="party-img" alt="${party.party_name}">
                    <h3 class="party-name">${party.party_name}</h3>
                    <button class="vote-btn" onclick="openModal(${party.party_id}, '${party.party_name}', '${party.logo}')">Vote</button>
                `;
                grid.appendChild(card);
            });
        })
        .catch(err => console.error("Error loading parties:", err));
}

let selectedPartyId = null;

function openModal(partyId, partyName, partyLogo) {
    const token = localStorage.getItem("jwt");
    if (!token) {
        alert("You must login to vote!");
        return;
    }

    selectedPartyId = partyId;

    // Postavi sliku i ime stranke u modal
    document.getElementById("modalPartyImage").src = `${BASE_URL}/uploads/${partyLogo}`;
    const partySelect = document.getElementById("partySelect");
    partySelect.innerHTML = `<option value="${partyId}">${partyName}</option>`;

    // Reset dropdown kandidata
    const candidateSelect = document.getElementById("candidateSelect");
    candidateSelect.innerHTML = `<option value="">Select Candidate</option>`;

    // Fetch kandidata za izabranu stranku
    fetch(`${BASE_URL}/candidates/party/${partyId}`)
    .then(res => {
        if (!res.ok) throw new Error("Failed to load candidates");
        return res.json();
    })
    .then(candidates => {
        console.log("Candidates:", candidates);
        candidates.forEach(c => {
            const option = document.createElement("option");
            option.value = c.candidate_id;
            option.textContent = c.full_name;
            candidateSelect.appendChild(option);
        });
    })
    .catch(err => console.error("Error loading candidates:", err));


    document.getElementById("voteModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("voteModal").style.display = "none";
}

window.onclick = function(e) {
    if (e.target == document.getElementById("voteModal")) closeModal();
}

function submitVote() {
    const token = localStorage.getItem("jwt");
    const candidateId = document.getElementById("candidateSelect").value;

    if (!token) {
        alert("You must login to vote!");
        return;
    }
    if (!candidateId) {
        alert("Please select a candidate");
        return;
    }

    fetch(`${BASE_URL}/votes`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${token}`
        },
        body: JSON.stringify({ candidate_id: candidateId })
    })
    .then(async res => {
        let data;
        try {
            data = await res.json(); // pokušava parsirati JSON
        } catch {
            data = null; // ako nije JSON, ostaje null
        }
        if (!res.ok) throw new Error(data?.error || "Failed to submit vote");
        return data;
    })
    .then(data => {
        alert("Vote submitted successfully!");
        closeModal();
    })
    .catch(err => {
        toastr.error(err.message || "You have already voted");
    });
}



$(document).ready(function() {

  $("main#spapp > section").height($(document).height() - 60);

  var app = $.spapp({
    defaultView: "home",  
    pageNotFound: "error_404",
    templateDir: "Front-End/tpl/" 
  });

  app.route({ view: "home", load: "home.html" });
  app.route({ view: "vote", load: "vote.html" });
  app.route({ view: "about", load: "about.html" });
  app.route({ view: "stranke", load: "stranke.html" });
  app.route({ view: "contact", load: "contact.html" });
  app.route({ view: "login", load: "login.html" });
  app.route({ view: "sign-up", load: "sign-up.html" });
  app.route({ view: "admin-panel", load: "admin-panel.html" });

  app.run();
  updateAuthUI();
});

function updateAuthUI() {
    const token = localStorage.getItem("jwt");
    const user = localStorage.getItem("user");

    if (token && user) {
        $(".user-dropdown").show();
        $("a[href='#login']").hide();
    } else {
        $(".user-dropdown").hide();
        $("a[href='#login']").show();
    }
}

//acciount looging out

$(document).on("click", ".user-icon", function (e) {
    e.stopPropagation();
    $("#userMenu").toggleClass("show");
});

$(document).on("click", function () {
    $("#userMenu").removeClass("show");
});

$(document).on("click", "#logoutBtn", function () {
    localStorage.removeItem("jwt");
    localStorage.removeItem("user");

    updateAuthUI();
    toastr.success("You have successfully logged out");
    window.location.hash = "#login";
});





// NAVBAR SCRIPT
window.addEventListener('DOMContentLoaded', event => {

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink')
        } else {
            navbarCollapsible.classList.add('navbar-shrink')
        }

    };

    // Shrink the navbar 
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener('scroll', navbarShrink);

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            rootMargin: '0px 0px -40%',
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

    // Activate SimpleLightbox plugin for portfolio items
    new SimpleLightbox({
        elements: '#portfolio a.portfolio-box'
    });

});

function showSidebar(){
      const sidebar = document.querySelector('.sidebar')
      sidebar.style.display = 'flex'
    }
    function hideSidebar(){
      const sidebar = document.querySelector('.sidebar')
      sidebar.style.display = 'none'
    }

let lastScroll = 0;
  const footer = document.querySelector('.footer-container');

  window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;

    if (currentScroll > lastScroll) {
      
      footer.classList.add('visible');
    } else {
      footer.classList.remove('visible');
    }

    lastScroll = currentScroll;
  });

// LOGIN HANDLER FOR SPA
$(document).on("click", "#loginBtn", function(e) {
    e.preventDefault();

    const email = $("#email").val();
    const password = $("#password").val();

    fetch(`${BASE_URL}/auth/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password })
    })
    .then(res => res.json())
    .then(res => {
        if (!res.token) {  // provjerava da li je login uspio
            alert(res.error || "Invalid login");
            return;
        }

        // Save JWT + user
        localStorage.setItem("jwt", res.token);
        localStorage.setItem("user", JSON.stringify(res.user));

        updateAuthUI();
        toastr.success("You have successfully logged  in")

        if (res.user.role === "admin") {
            window.location.hash = "#admin-panel";
        } else {
            window.location.hash = "#home";
        }
    })
    .catch(err => {
        console.error("Login error:", err);
        toastr.error("server error 404")
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

// SIGNUP HANDLER
$(document).on("click", "#signupBtn", function(e) {
    e.preventDefault();

    const full_name = $("#fullname").val();
    const jmbg = $("#jmbg").val();
    const email = $("#signup_email").val();
    const password = $("#signup_password").val();

    fetch(`${BASE_URL}/auth/register`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ full_name, jmbg, email, password })
    })
    .then(res => res.json())
    .then(res => {
        if (res.error) {  // Da li je nastao error?
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




//Konekcija sa bazom za stranke


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