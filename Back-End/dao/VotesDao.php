<?php
require_once 'BaseDao.php';

class VotesDao extends BaseDao {

    public function __construct() {
        parent::__construct("votes");
    }

    public function addVote($data) {
        return $this->insert($data); // user_id + candidate_id
    }

    public function getVoteDetailsWithParty() {
        $sql = "SELECT v.vote_id, v.user_id, v.candidate_id, 
                       c.party_id, p.party_name
                FROM votes v
                JOIN candidates c ON v.candidate_id = c.candidate_id
                JOIN parties p ON c.party_id = p.party_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVoteWithPartyById($id) {
        $sql = "SELECT v.vote_id, v.user_id, v.candidate_id, 
                       c.party_id, p.party_name
                FROM votes v
                JOIN candidates c ON v.candidate_id = c.candidate_id
                JOIN parties p ON c.party_id = p.party_id
                WHERE v.vote_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countVotesByCandidate($candidate_id) {
        $sql = "SELECT COUNT(*) AS total FROM votes WHERE candidate_id = :cid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cid' => $candidate_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function countVotesByUser($user_id) {
        $sql = "SELECT COUNT(*) AS total FROM votes WHERE user_id = :uid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function resetVotes() {
        $stmt = $this->conn->prepare("TRUNCATE TABLE votes");
        return $stmt->execute();
    }

    public function getVotingReport() {
    $sql = "SELECT 
                u.user_id,
                u.full_name AS user_name,
                c.candidate_id,
                c.full_name AS candidate_name,
                c.position,
                p.party_id,
                p.party_name,
                (SELECT COUNT(*) FROM votes v2 WHERE v2.candidate_id = c.candidate_id) AS total_votes_for_candidate
            FROM votes v
            JOIN users u ON v.user_id = u.user_id
            JOIN candidates c ON v.candidate_id = c.candidate_id
            JOIN parties p ON c.party_id = p.party_id
            ORDER BY u.user_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
