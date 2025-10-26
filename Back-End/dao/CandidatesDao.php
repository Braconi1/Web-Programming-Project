<?php
require_once 'BaseDao.php';

class CandidatesDao extends BaseDao {
    public function __construct() {
        parent::__construct('Candidates');
    }

    public function getByPartyId($party_id) {
        $stmt = $this->connection->prepare("SELECT * FROM Candidates WHERE party_id = :party_id");
        $stmt->bindParam(':party_id', $party_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
