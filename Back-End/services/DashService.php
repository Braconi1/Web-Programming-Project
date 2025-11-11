<?php
require_once __DIR__ . '/../dao/UsersDao.php';
require_once __DIR__ . '/../dao/CandidatesDao.php';
require_once __DIR__ . '/../dao/VotesDao.php';

class DashService {
    private $usersDao;
    private $candidatesDao;
    private $votesDao;

    public function __construct() {
        $this->usersDao = new UsersDao();
        $this->candidatesDao = new CandidatesDao();
        $this->votesDao = new VotesDao();
    }

    public function getStats() {
        return [
            "totalUsers" => count($this->usersDao->getAll()),
            "totalCandidates" => count($this->candidatesDao->getAll()),
            "totalVotes" => count($this->votesDao->getAll())
        ];
    }

    public function resetVotes() {
        return $this->votesDao->resetVotes();
    }
}
?>
