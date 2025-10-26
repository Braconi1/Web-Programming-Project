<?php
require_once 'BaseDao.php';

class VotesDao extends BaseDao {
    public function __construct() {
        parent::__construct('Votes');
    }

    public function getVotesByCandidate($candidate_id) {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as total_votes FROM Votes WHERE candidate_id = :candidate_id");
        $stmt->bindParam(':candidate_id', $candidate_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVotesByUser($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM Votes WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
