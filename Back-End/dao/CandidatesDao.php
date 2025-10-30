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
}
?>
