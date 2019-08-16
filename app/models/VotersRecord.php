<?php 
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    use \DateTime;

    class VotersRecord extends Model {
        public $vote_id, $post_id, $user_id, $vote_option, $created_at, $hasVoted;
        private $users_vote;

        public function __construct() {
            parent::__construct('voters_record');
        }

        public function isClosed($post_id) {
            $duration = $this->query("SELECT duration FROM posts WHERE post_id = ?", [$post_id])->getResult()[0]->duration;
            
            $currentTime = new DateTime(date('Y-m-d H:i:s'));
            $time_stated = new DateTime($duration);
            $poll_time_diff = $currentTime->diff($time_stated);
           
            //If the duration for the poll has elapsed, stop all vote
            if ($currentTime > $time_stated) {
                return true;
            }
            return false;
        }

        private function hasVoted($post_id) {
            $sql = "SELECT vote_option FROM voters_record WHERE post_id = ? AND user_id = ? ";
            $status = $this->query($sql, [$post_id, Session::get(USER_SESSION_NAME)])->getResult();
            if(!empty($status)) {
                $this->hasVoted = true;
                $this->users_vote = $status[0]->vote_option;
            } else {
                $this->hasVoted = false;
            }
            return $this->hasVoted;
        }

        public function toggleVote($post_id) {
            if($this->hasVoted($post_id)) {
                if($this->users_vote == $this->vote_option) {
                    if($this->delete('post_id = ? AND user_id = ?', [$post_id, Session::get(USER_SESSION_NAME)])) {
                        return $this->reCalculateVote($post_id);
                    }
                } else {
                    $this->update('post_id = ? AND user_id = ?', 
                        [$post_id, Session::get(USER_SESSION_NAME)], ['vote_option' => $this->vote_option]);
                    return $this->reCalculateVote($post_id);
                }
            } else {
                $this->insert([
                    'post_id' => $post_id, 
                    'user_id' => Session::get(USER_SESSION_NAME), 
                    'vote_option' => $this->vote_option, 
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return $this->reCalculateVote($post_id);
            }
        }

        private function reCalculateVote(int $post_id) {
            if($this->hasVoted) {
                if($this->users_vote == $this->vote_option) {
                    $sql = "UPDATE posts SET {$this->vote_option} = (SELECT count(vote_option) 
                                            FROM voters_record WHERE post_id = ? AND vote_option = ?)
                                WHERE post_id = ?";
                    $this->query($sql, [$post_id, $this->vote_option, $post_id]);
                } else {
                    $sql = "UPDATE posts SET {$this->users_vote} = (SELECT count(vote_option) 
                                            FROM voters_record WHERE post_id = ? AND vote_option = ?), 
                                             {$this->vote_option} = (SELECT count(vote_option) 
                                            FROM voters_record WHERE post_id = ? AND vote_option = ?)
                                WHERE post_id = ?";
                    $this->query($sql, [$post_id, $this->users_vote, $post_id, $this->vote_option, $post_id]);
                }
            } else {
                $sql = "UPDATE posts SET {$this->vote_option} = (SELECT count(vote_option) 
                                            FROM voters_record WHERE post_id = ? AND vote_option = ?)
                            WHERE post_id = ?";
                        $this->query($sql, [$post_id, $this->vote_option, $post_id]);
            }
            return true;
        }

    }

?>