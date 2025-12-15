<?php
require_once __DIR__ . '/../dao/CandidatesDao.php';
require_once 'BaseService.php';

class CandidatesService extends BaseService {

    public function __construct() {
        parent::__construct(new CandidatesDao());
    }

    public function uploadImage($id, $fileName) {
        return $this->dao->update($id, ["image" => $fileName], "candidate_id");
    }

    public function getByPartyId($partyId) {
        return $this->dao->getByPartyId($partyId);
    }

    public function getAll() {
    return $this->dao->getAllCandidates();
}

}
