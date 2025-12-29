class PartyService {
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

    async getAllParties() {
        try {
            const response = await fetch(`${this.baseURL}/parties`);

            if (!response.ok) {
                throw new Error("Failed to fetch parties");
            }

            return await response.json();
        } catch (error) {
            console.error("Error loading parties:", error);
            throw error;
        }
    }

    async getPartyById(partyId) {
        try {
            const response = await fetch(`${this.baseURL}/parties/${partyId}`);

            if (!response.ok) {
                throw new Error("Party not found");
            }

            return await response.json();
        } catch (error) {
            console.error("Error fetching party:", error);
            throw error;
        }
    }

    async addParty(partyData) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/parties`, {
                method: "POST",
                headers: headers,
                body: JSON.stringify(partyData)
            });

            if (!response.ok) {
                throw new Error("Failed to add party");
            }

            return await response.json();
        } catch (error) {
            console.error("Error adding party:", error);
            throw error;
        }
    }

    async updateParty(partyId, partyData) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/parties/${partyId}`, {
                method: "PUT",
                headers: headers,
                body: JSON.stringify(partyData)
            });

            if (!response.ok) {
                throw new Error("Failed to update party");
            }

            return await response.json();
        } catch (error) {
            console.error("Error updating party:", error);
            throw error;
        }
    }

    async deleteParty(partyId) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/parties/${partyId}`, {
                method: "DELETE",
                headers: headers
            });

            if (!response.ok) {
                throw new Error("Failed to delete party");
            }

            return await response.json();
        } catch (error) {
            console.error("Error deleting party:", error);
            throw error;
        }
    }

    async searchParties(keyword) {
        try {
            const response = await fetch(`${this.baseURL}/parties/search?q=${encodeURIComponent(keyword)}`);

            if (!response.ok) {
                throw new Error("Search failed");
            }

            return await response.json();
        } catch (error) {
            console.error("Error searching parties:", error);
            throw error;
        }
    }

    async getCandidateCount(partyId) {
        try {
            const response = await fetch(`${this.baseURL}/parties/${partyId}/candidate-count`);

            if (!response.ok) {
                throw new Error("Failed to get candidate count");
            }

            const data = await response.json();
            return data.count || 0;
        } catch (error) {
            console.error("Error getting candidate count:", error);
            return 0;
        }
    }
}

// Export singleton instance
const partyService = new PartyService();