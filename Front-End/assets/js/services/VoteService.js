class VoteService {
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

    async submitVote(candidateId) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/votes`, {
                method: "POST",
                headers: headers,
                body: JSON.stringify({ candidate_id: candidateId })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || "Failed to submit vote");
            }

            return data;
        } catch (error) {
            console.error("Error submitting vote:", error);
            throw error;
        }
    }

    async getAllVotes() {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/votes`, {
                headers: headers
            });

            if (!response.ok) {
                throw new Error("Failed to fetch votes");
            }

            return await response.json();
        } catch (error) {
            console.error("Error fetching votes:", error);
            throw error;
        }
    }

    async getVoteById(voteId) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/votes/${voteId}`, {
                headers: headers
            });

            if (!response.ok) {
                throw new Error("Vote not found");
            }

            return await response.json();
        } catch (error) {
            console.error("Error fetching vote:", error);
            throw error;
        }
    }

    async deleteVote(voteId) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/votes/${voteId}`, {
                method: "DELETE",
                headers: headers
            });

            if (!response.ok) {
                throw new Error("Failed to delete vote");
            }

            return await response.json();
        } catch (error) {
            console.error("Error deleting vote:", error);
            throw error;
        }
    }

    async getVotingReport() {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/votes/report`, {
                headers: headers
            });

            if (!response.ok) {
                throw new Error("Failed to get voting report");
            }

            return await response.json();
        } catch (error) {
            console.error("Error fetching voting report:", error);
            throw error;
        }
    }

    
    async getCandidateVoteCount(candidateId) {
        try {
            const response = await fetch(`${this.baseURL}/votes/candidate/${candidateId}/count`);

            if (!response.ok) {
                throw new Error("Failed to get vote count");
            }

            return await response.json();
        } catch (error) {
            console.error("Error getting candidate vote count:", error);
            throw error;
        }
    }

    async getUserVoteCount(userId) {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/votes/user/${userId}/count`, {
                headers: headers
            });

            if (!response.ok) {
                throw new Error("Failed to get user vote count");
            }

            return await response.json();
        } catch (error) {
            console.error("Error getting user vote count:", error);
            throw error;
        }
    }

    async hasUserVoted() {
        const user = JSON.parse(localStorage.getItem("user"));
        if (!user) return false;

        try {
            const result = await this.getUserVoteCount(user.user_id);
            return result.total_votes > 0;
        } catch (error) {
            console.error("Error checking if user voted:", error);
            return false;
        }
    }

    async resetAllVotes() {
        const headers = this.getAuthHeaders();
        if (!headers) return null;

        try {
            const response = await fetch(`${this.baseURL}/votes/reset`, {
                method: "POST",
                headers: headers
            });

            if (!response.ok) {
                throw new Error("Failed to reset votes");
            }

            return await response.json();
        } catch (error) {
            console.error("Error resetting votes:", error);
            throw error;
        }
    }
}

// Export singleton instance
const voteService = new VoteService();