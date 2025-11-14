<?php
require_once 'BaseDao.php';

class VotesDao extends BaseDao {

    public function __construct() {
        parent::__construct("votes");
    }

    public function getVoteById($id) {
        return $this->getById($id, 'vote_id');
    }

    public function addVote($data) {
        return $this->insert($data);
    }

    public function updateVote($id, $data) {
        return $this->update($id, $data, 'vote_id');
    }

    public function deleteVote($id) {
        return $this->delete($id, 'vote_id');
    }

    public function getAllVotes() {
        return $this->getAll();
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
}
