<?php
require_once 'BaseDao.php';

class CandidatesDao extends BaseDao {

    public function __construct() {
        parent::__construct("candidates");
    }

    public function getCandidateById($id) {
        return $this->getById($id, 'candidate_id');
    }

    public function addCandidate($data) {
        return $this->insert($data);
    }

    public function updateCandidate($id, $data) {
        return $this->update($id, $data, 'candidate_id');
    }

    public function deleteCandidate($id) {
        return $this->delete($id, 'candidate_id');
    }

    public function getAllCandidates() {
        return $this->getAll();
    }

    public function getByPartyId($partyId) {
    try {
        $query = "SELECT * FROM candidates WHERE party_id = :party_id";
        $stmt = $this->conn->prepare($query); // promijenjeno
        $stmt->bindValue(":party_id", (int)$partyId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching candidates: " . $e->getMessage());
        throw $e; // da ruta može vratiti JSON error
    }
    }

}
?>
