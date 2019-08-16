<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class PostPromotes extends Model {
        public $promote_id, $status, $post_id, $user_id;

        public function __construct() {
            parent::__construct('post_promotes');
        }

        public function getSumOfPromotes($post_id) {
            $sql = "SELECT SUM(status) AS total FROM post_promotes WHERE post_id = ?";
            $result = $this->query($sql, [$post_id])->getResult()[0]->total;
            if($result !== null) {
                return $result;
            }
            return 0;
        }

        private function updatePromoteTotal($post_id) {
            $this->query("UPDATE posts SET post_promotes = (SELECT sum(status) FROM post_promotes WHERE post_id = ?) 
            WHERE post_id = ?", [$post_id, $post_id]);
        }

        private function checkStatus($post_id) {
            $sql = "SELECT status FROM post_promotes WHERE post_id = ? AND user_id = ?";
            $result = $this->query($sql, [$post_id, Session::get(USER_SESSION_NAME)])->getResult();
            if(count($result) > 0) {
                return $result[0]->status;
            }
            return 0;
        }

        public function demotePost() {
            if($this->checkStatus($this->post_id) == -1) {
                if($this->delete('post_id = ? AND user_id = ?', [$this->post_id, Session::get(USER_SESSION_NAME)])) {
                    $this->updatePromoteTotal($this->post_id);
                    return true;
                } return false;
            } elseif($this->checkStatus($this->post_id) == 1) {
                if($this->update('post_id = ? AND user_id = ?', 
                        [$this->post_id, Session::get(USER_SESSION_NAME)], ['status' => -1])) {
                    $this->updatePromoteTotal($this->post_id);
                    return true;
                } return false;
            } else {
                if($this->insert([
                    'status' => -1,
                    'post_id' => $this->post_id,
                    'user_id' => Session::get(USER_SESSION_NAME)
                ])) {
                    $this->updatePromoteTotal($this->post_id);
                    return true;
                }
            }
            return false;
        }

        public function promotePost() {
            if($this->checkStatus($this->post_id) == 1) {
                if($this->delete('post_id = ? AND user_id = ?', [$this->post_id, Session::get(USER_SESSION_NAME)])) {
                    $this->updatePromoteTotal($this->post_id);
                    return true;
                } return false;
            } elseif($this->checkStatus($this->post_id) == -1) {
                if($this->update('post_id = ? AND user_id = ?', 
                        [$this->post_id, Session::get(USER_SESSION_NAME)], ['status' => 1])) {
                    $this->updatePromoteTotal($this->post_id);
                    return true;
                } return false;
            } else {
                if($this->insert([
                    'status' => 1,
                    'post_id' => $this->post_id,
                    'user_id' => Session::get(USER_SESSION_NAME)
                ])) {
                    $this->updatePromoteTotal($this->post_id);
                    return true;
                }
            }
            return false;
        }

    }
?>