<?php
require_once __DIR__ . '/../dao/VotesDao.php';
require_once 'BaseService.php';

class VoteService extends BaseService {

    public function __construct() {
        parent::__construct(new VotesDao());
    }

    public function addVote($data) {
        return $this->dao->addVote($data);
    }

    public function hasVoted($user_id) {
        return $this->dao->countVotesByUser($user_id) > 0;
    }

    public function countForCandidate($candidate_id) {
        return $this->dao->countVotesByCandidate($candidate_id);
    }

    public function resetVotes() {
        return $this->dao->resetVotes();
    }

    public function getReport() {
        return $this->dao->getVoteDetails();
    }
}
