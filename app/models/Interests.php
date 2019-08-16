<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class Interests extends Model {
        public $interest_id, $user_id, $interest;

        public function __construct() {
            parent::__construct('interests');
        }

        public function fetchInterests($user_id) {
            $sql = "SELECT interest FROM interests WHERE user_id = ?";
            return $this->query($sql, [$user_id])->getResult();
        }

        public function fetchInterestCount($user_id) {
            $sql = "SELECT count(interest) as c FROM interests WHERE user_id = ?";
            return $this->query($sql, [$user_id])->getResult()[0]->c;
        }

        private function checkInterest($user_id) {
            $sql = "SELECT interest FROM interests WHERE user_id = ? AND interest = ?";
            return $this->query($sql, [$user_id, $this->interest])->getResult();
        }

        public function insertInterest() {
            if(count($this->checkInterest(Session::get(USER_SESSION_NAME))) > 0) {
                $this->deleteInterest();
                return true;
            } else {
                return $this->insert([
                    'user_id' => Session::get(USER_SESSION_NAME),
                    'interest' => $this->interest
                ]);
            }
        }
    
        public function deleteInterest() {
            $sql = "DELETE FROM interests WHERE user_id = ".Session::get(USER_SESSION_NAME)." AND interest = ?";
            return $this->query($sql, [$this->interest])->getResult();
        }
    }
?>