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
}
?>
