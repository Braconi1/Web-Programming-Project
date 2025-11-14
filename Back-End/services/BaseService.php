<?php

class BaseService {
    protected $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id, $col = 'id') {
        return $this->dao->getById($id, $col);
    }

    public function add($data) {
        return $this->dao->insert($data);
    }

    public function update($id, $data, $col = 'id') {
        return $this->dao->update($id, $data, $col);
    }

    public function delete($id, $col = 'id') {
        return $this->dao->delete($id, $col);
    }
}
