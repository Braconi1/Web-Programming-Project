<?php
require_once 'BaseDao.php';

class PartiesDao extends BaseDao {

    public function __construct() {
        parent::__construct("parties");
    }

    public function getPartyById($id) {
        return $this->getById($id, 'party_id');
    }

    public function addParty($data) {
        return $this->insert($data);
    }

    public function updateParty($id, $data) {
        return $this->update($id, $data, 'party_id');
    }

    public function deleteParty($id) {
        return $this->delete($id, 'party_id');
    }

    public function getAllParties() {
        return $this->getAll();
    }

    public function getCandidateCount($party_id) {
        $sql = "SELECT COUNT(*) AS total FROM candidates WHERE party_id = :pid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':pid' => $party_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function search($keyword) {
        $sql = "SELECT * FROM parties WHERE party_name LIKE :kw";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':kw' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSortedParties() {
        $sql = "SELECT * FROM parties ORDER BY party_name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
