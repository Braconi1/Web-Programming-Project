// Base URL 
const BASE_URL = "http://localhost/ElvirPandur/WEB-PROGRAMMING-PROJECT/Back-End";

// DOM LOADING FUNCTIONS
document.addEventListener("DOMContentLoaded", () => {
    loadParties();
});

function loadParties() {
    partyService.getAllParties()
        .then(data => {
            const grid = document.getElementById("partyGrid");
            if (!grid) return;
            
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
        .catch(err => {
            console.error("Error loading parties:", err);
            toastr.error("Failed to load parties");
        });
}

let selectedPartyId = null;

function openModal(partyId, partyName, partyLogo) {
    const token = localStorage.getItem("jwt");
    if (!token) {
        toastr.error("You must login to vote!");
        window.location.hash = "#login";
        return;
    }

    selectedPartyId = partyId;

    // Set party image and name in modal
    document.getElementById("modalPartyImage").src = `${BASE_URL}/uploads/${partyLogo}`;
    const partySelect = document.getElementById("partySelect");
    partySelect.innerHTML = `<option value="${partyId}">${partyName}</option>`;

    // Reset candidate dropdown
    const candidateSelect = document.getElementById("candidateSelect");
    candidateSelect.innerHTML = `<option value="">Select Candidate</option>`;

    // Fetch candidates using CandidateService
    candidateService.getCandidatesByParty(partyId)
        .then(candidates => {
            candidates.forEach(c => {
                const option = document.createElement("option");
                option.value = c.candidate_id;
                option.textContent = c.full_name;
                candidateSelect.appendChild(option);
            });
        })
        .catch(err => {
            console.error("Error loading candidates:", err);
            toastr.error("Failed to load candidates");
        });

    document.getElementById("voteModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("voteModal").style.display = "none";
}


window.onclick = function(e) {
    if (e.target == document.getElementById("voteModal")) closeModal();
}

function submitVote() {
    const candidateId = document.getElementById("candidateSelect").value;

    // CLIENT-SIDE VALIDATION
    const validation = ValidationHelper.validateCandidateSelection(candidateId);
    if (!validation.isValid) {
        toastr.error(validation.message);
        return;
    }

    voteService.submitVote(candidateId)
        .then(data => {
            toastr.success("Vote submitted successfully!");
            closeModal();
        })
        .catch(err => {
            toastr.error(err.message || "Failed to submit vote");
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

    // Reload parties when navigating to vote page
    $(window).on('hashchange', function() {
        const currentView = window.location.hash.replace('#', '');
        if (currentView === 'vote') {
            setTimeout(function() {
                if (document.getElementById('partyGrid')) {
                    loadParties();
                }
            }, 300);
        }
    });
    
    // Initial load if on vote page
    if (window.location.hash === '#vote') {
        setTimeout(function() {
            if (document.getElementById('partyGrid')) {
                loadParties();
            }
        }, 500);
    }
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