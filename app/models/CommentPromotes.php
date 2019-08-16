<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class CommentPromotes extends Model {
        public $promote_id, $status, $comment_id, $post_id, $user_id;

        public function __construct() {
            parent::__construct('comment_promotes');
        }

        public function getSumOfPromotes($comment_id) { 
            $sql = "SELECT SUM(status) AS total FROM comment_promotes WHERE comment_id = ?";
            $result = $this->query($sql, [$this->comment_id])->getResult()[0]->total;
            if($result !== null) {
                return $result;
            }
            return 0;
        }

        private function updatePromoteTotal($comment_id) {
            $this->query("UPDATE comments SET comment_promotes = (SELECT sum(status) FROM comment_promotes WHERE comment_id = ?) 
            WHERE comment_id = ?", [$this->comment_id, $this->comment_id]);
        }

        private function checkStatus($comment_id) {
            $sql = "SELECT status FROM comment_promotes WHERE comment_id = ? AND user_id = ?";
            $result = $this->query($sql, [$this->comment_id, Session::get(USER_SESSION_NAME)])->getResult();
            if(count($result) > 0) {
                return $result[0]->status;
            }
            return 0;
        }

        public function demoteComment() {
            if($this->checkStatus($this->comment_id) == -1) {
                if($this->delete('comment_id = ? AND user_id = ?', [$this->comment_id, Session::get(USER_SESSION_NAME)])) {
                    $this->updatePromoteTotal($this->comment_id);
                    return true;
                } return false;
            } elseif($this->checkStatus($this->comment_id) == 1) {
                if($this->update('comment_id = ? AND user_id = ?', 
                        [$this->comment_id, Session::get(USER_SESSION_NAME)], ['status' => -1])) {
                    $this->updatePromoteTotal($this->comment_id);
                    return true;
                } return false;
            } else {
                if($this->insert([
                    'status' => -1,
                    'comment_id' => $this->comment_id,
                    'post_id' => $this->post_id,
                    'user_id' => Session::get(USER_SESSION_NAME)
                ])) {
                    $this->updatePromoteTotal($this->comment_id);
                    return true;
                }
            }
            return false;
        }

        public function promoteComment() {
            if($this->checkStatus($this->comment_id) == 1) {
                if($this->delete('comment_id = ? AND user_id = ?', [$this->comment_id, Session::get(USER_SESSION_NAME)])) {
                    $this->updatePromoteTotal($this->comment_id);
                    return true;
                } return false;
            } elseif($this->checkStatus($this->comment_id) == -1) {
                if($this->update('comment_id = ? AND user_id = ?', 
                        [$this->comment_id, Session::get(USER_SESSION_NAME)], ['status' => 1])) {
                    $this->updatePromoteTotal($this->comment_id);
                    return true;
                } return false;
            } else {
                if($this->insert([
                    'status' => 1,
                    'comment_id' => $this->comment_id,
                    'post_id' => $this->post_id,
                    'user_id' => Session::get(USER_SESSION_NAME)
                ])) { 
                    $this->updatePromoteTotal($this->comment_id);
                    return true;
                }
            }
            return false;
        }

    }
?>