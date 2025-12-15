<?php
require_once __DIR__ . '/../dao/VotesDao.php';
require_once 'BaseService.php';

class VoteService extends BaseService {

    public function __construct() {
        parent::__construct(new VotesDao());
    }

    public function addVote($data) {
    $voteData = [
        'user_id' => $data['user_id'],
        'candidate_id' => $data['candidate_id']
    ];
    return $this->dao->addVote($voteData);
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
        return $this->dao->getVoteDetailsWithParty(); // Join
    }

    public function getVotingReport(){
        return $this->dao->getVotingReport();
    }

    public function countVotesByUser($user_id) {
        return $this->dao->countVotesByUser($user_id);
    }

    public function getById($id, $col = 'vote_id') {
        return $this->dao->getVoteWithPartyById($id);
    }

    public function delete($id, $col = 'vote_id') {
        return $this->dao->delete($id, "vote_id");
    }
}
?>
