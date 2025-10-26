<?php
require_once 'BaseDao.php';

class PartiesDao extends BaseDao {
    public function __construct() {
        parent::__construct('Parties');
    }

    public function getByName($name) {
        $stmt = $this->connection->prepare("SELECT * FROM Parties WHERE party_name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
