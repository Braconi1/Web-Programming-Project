<?php
require_once __DIR__ . '/../dao/PartiesDao.php';
require_once 'BaseService.php';

class PartiesService extends BaseService {

    public function __construct() {
        parent::__construct(new PartiesDao());
    }

    public function addParty($data) {
        return $this->dao->insert($data);
    }

    public function updateParty($id, $data) {
        return $this->dao->update($id, $data, "party_id");
    }

    public function deleteParty($id) {
        return $this->dao->delete($id, "party_id");
    }

    public function getCandidateCount($party_id) {
        return $this->dao->getCandidateCount($party_id);
    }

    public function search($keyword) {
        return $this->dao->search($keyword);
    }

    public function getSorted() {
        return $this->dao->getSortedParties();
    }
}
