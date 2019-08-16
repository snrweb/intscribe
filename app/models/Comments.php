<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class Comments extends Model {
        public $comment_id, $comment, $post_id, $user_id, $post_comments, $comment_promotes, $created_at;

        public function __construct() {
            parent::__construct('comments');
        }

        public function insertComment(int $user_id) {
            $n = new Notifications();
            if($this->insert([
                'comment' => html_entity_decode($this->comment),
                'post_id' => $this->post_id,
                'user_id' => Session::get(USER_SESSION_NAME),
                'created_at' => date('Y-m-d H:i:s')
            ])) {

                if(Session::get(USER_SESSION_NAME) != $user_id) {
                    $comment_id = $this->db->getlastId();
                    $n->addCommentNotification($user_id, $this->post_id, $comment_id); 
                }

                return $this->query("UPDATE posts SET post_comments = 
                                    (SELECT count(comment) FROM comments WHERE post_id = ? AND isDeleted <> 1) 
                                    WHERE post_id = ?", [$this->post_id, $this->post_id]);
            }
        }

        public function updateComment() {
            return $this->query("UPDATE comments SET comment = ? WHERE comment_id = ? AND user_id = ? ", 
                                    [html_entity_decode($this->comment), $this->comment_id, Session::get(USER_SESSION_NAME)]);
        }

        public function fetchComment($post_id, $start = 0) {
            if(Session::exists(USER_SESSION_NAME)) {
                $user = Session::get(USER_SESSION_NAME);
                $sql = "SELECT comments.comment_id, comments.comment, comments.user_id,
                        comments.comment_promotes, comments.created_at, users.profile_image,
                        users.username, follow.follow_id, comment_promotes.status
                        FROM comments
                        LEFT JOIN users
                        ON comments.user_id = users.user_id
                        LEFT JOIN follow
                        ON comments.user_id = follow.following
                        AND follow.follower = $user
                        LEFT JOIN comment_promotes
                        ON comments.comment_id = comment_promotes.comment_id
                        AND comment_promotes.user_id = $user
                        WHERE comments.post_id = $post_id AND isDeleted <> 1 LIMIT $start, 2";
                return $this->query($sql)->getResult();
            } else {
                $sql = "SELECT comments.comment_id, comments.comment, comments.user_id,
                        comments.comment_promotes, comments.created_at, users.profile_image,
                        users.username FROM comments
                        LEFT JOIN users
                        ON comments.user_id = users.user_id
                        WHERE comments.post_id = $post_id AND isDeleted <> 1 LIMIT 2";
                return $this->query($sql)->getResult();
            }
        }

        public function deleteComment() {
            if($this->update('comment_id = ? AND user_id = ?', 
                            [$this->comment_id, Session::get(USER_SESSION_NAME)], 
                            ['isDeleted' => 1])) {
                $this->query("DELETE FROM comment_promotes WHERE comment_id = ? AND user_id = ?", 
                                [$this->comment_id, Session::get(USER_SESSION_NAME)]);
            }
            return $this->query("UPDATE posts SET post_comments = 
                                    (SELECT count(comment) FROM comments WHERE post_id = ? AND isDeleted <> 1) 
                                    WHERE post_id = ?", [$this->post_id, $this->post_id]);
        }

    }

?>