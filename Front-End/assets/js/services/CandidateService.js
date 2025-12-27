class CandidateService {
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

    async getAllCandidates() {
        try {
            const response = await fetch(`${this.baseURL}/candidates`);

            if (!response.ok) {
                throw new Error("Failed to fetch candidates");
            }

            return await response.json();
        } catch (error) {
            console.error("Error loading candidates:", error);
            throw error;
        }
    }

    async getCandidateById(candidateId) {
        try {
            const response = await fetch(`${this.baseURL}/candidates/${candidateId}`);

            if (!response.ok) {
                throw new Error("Candidate not found");
            }

            return await response.json();
        } catch (error) {
            console.error("Error fetching candidate:", error);
            throw error;
        }
    }

    async getCandidatesByParty(partyId) {
        try {
            const response = await fetch(`${this.baseURL}/candidates/party/${partyId}`);

            if (!response.ok) {
                throw new Error("Failed to load candidates for this party");
            }

            return await response.json();
        } catch (error) {
            console.error("Error loading candidates by party:", error);
            throw error;
        }
    }

    async addCandidate(candidateData) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/candidates`, {
                method: "POST",
                headers: headers,
                body: JSON.stringify(candidateData)
            });

            if (!response.ok) {
                throw new Error("Failed to add candidate");
            }

            return await response.json();
        } catch (error) {
            console.error("Error adding candidate:", error);
            throw error;
        }
    }

    async updateCandidate(candidateId, candidateData) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/candidates/${candidateId}`, {
                method: "PUT",
                headers: headers,
                body: JSON.stringify(candidateData)
            });

            if (!response.ok) {
                throw new Error("Failed to update candidate");
            }

            return await response.json();
        } catch (error) {
            console.error("Error updating candidate:", error);
            throw error;
        }
    }

    async deleteCandidate(candidateId) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/candidates/${candidateId}`, {
                method: "DELETE",
                headers: headers
            });

            if (!response.ok) {
                throw new Error("Failed to delete candidate");
            }

            return await response.json();
        } catch (error) {
            console.error("Error deleting candidate:", error);
            throw error;
        }
    }
    
    async searchCandidates(keyword) {
        try {
            const response = await fetch(`${this.baseURL}/candidates/search?q=${encodeURIComponent(keyword)}`);

            if (!response.ok) {
                throw new Error("Search failed");
            }

            return await response.json();
        } catch (error) {
            console.error("Error searching candidates:", error);
            throw error;
        }
    }
}

// Export singleton instance
const candidateService = new CandidateService();