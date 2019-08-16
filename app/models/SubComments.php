<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class SubComments extends Model {
        public $sub_comment_id, $sub_comment, $post_id, $comment_id, $user_id, $created_at;

        public function __construct() {
            parent::__construct('sub_comments');
        }

        public function insertSubComment(int $user_id) {
            $n = new Notifications();
            $this->insert([
                'sub_comment' => html_entity_decode($this->sub_comment), 
                'user_id' => Session::get(USER_SESSION_NAME), 
                'created_at' => date('Y-m-d H:i:s'), 
                'post_id' => $this->post_id, 
                'comment_id' => $this->comment_id
            ]);

            if(Session::get(USER_SESSION_NAME) != $user_id) {
                $sub_comment_id = $this->db->getlastId();
                return $n->addSubCommentNotification($user_id, $this->post_id, $this->comment_id, $sub_comment_id); 
            }
             return true;
        }
        
        public function updateSubComment() {
            return $this->update('sub_comment_id = ? AND user_id = ?', 
                                [$this->sub_comment_id, Session::get(USER_SESSION_NAME)],  
                                ['sub_comment' => html_entity_decode($this->sub_comment)]);
        }

        public function getAllSubComments($post_id) {
            if(Session::exists(USER_SESSION_NAME)) {
                $user = Session::get(USER_SESSION_NAME);
                $sql = "SELECT sub_comments.sub_comment, sub_comments.sub_comment_id, sub_comments.comment_id,
                                sub_comments.user_id, sub_comments.created_at, users.profile_image, users.username
                                FROM sub_comments
                                LEFT JOIN users
                                ON sub_comments.user_id = users.user_id
                                WHERE sub_comments.post_id = $post_id AND isDeleted <> 1 ORDER BY sub_comment_id ASC";
                return $this->query($sql)->getResult();
            } else {
                $sql = "SELECT sub_comments.sub_comment, sub_comments.sub_comment_id, sub_comments.comment_id,
                                sub_comments.user_id, sub_comments.created_at, users.profile_image, users.username 
                                FROM sub_comments
                                LEFT JOIN users
                                ON sub_comments.user_id = users.user_id
                                WHERE sub_comments.post_id = $post_id AND isDeleted <> 1 ORDER BY sub_comment_id ASC";  
                return $this->query($sql)->getResult();          
            }
        }
        
        public function deleteSubComment() {
            return $this->update('sub_comment_id = ? AND user_id = ?', 
                            [$this->sub_comment_id, Session::get(USER_SESSION_NAME)], 
                            ['isDeleted' => 1]);
        }

    }

?>