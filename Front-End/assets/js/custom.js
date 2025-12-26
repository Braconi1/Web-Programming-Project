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
        toastr.error("You must login to vote!");
        return;
    }
    if (!candidateId) {
        toastr.error("Please select a candidate");
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
        toastr.success("Vote submitted successfully!");
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