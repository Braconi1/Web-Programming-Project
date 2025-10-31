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
        $stmt = $this->connection->prepare("SELECT COUNT(*) AS total FROM votes WHERE candidate_id = :candidate_id");
        $stmt->execute([':candidate_id' => $candidate_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resetVotes() {
        $stmt = $this->connection->prepare("TRUNCATE TABLE votes");
        return $stmt->execute();
    }
}
?>
