<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class Notifications extends Model {
        public $notification_id, $type, $receiver, $sender, $post_id, $comment_id, 
                $sub_comment_id, $status, $created_at;

        public function __construct() {
            parent::__construct('notifications');
        }

        public function addFollowNotification(int $receiver) {
            return $this->insert([
                'type' => 'Follow', 
                "sender" => Session::get(USER_SESSION_NAME), 
                "receiver" => $receiver, 
                "created_at" => date('Y-m-d H:i:s'), 
            ]);
        }

        public function removeFollowNotification(int $receiver) {
            $user_id = Session::get(USER_SESSION_NAME);
            return $this->delete('type = ? AND sender = ? AND receiver = ?', ['Follow', $user_id, $receiver]);
        }

        public function addBookmarkNotification(int $receiver, int $post_id) {
            return $this->insert([
                'type' => 'Bookmark', 
                "sender" => Session::get(USER_SESSION_NAME), 
                "receiver" => $receiver, 
                "post_id" => $post_id,
                "created_at" => date('Y-m-d H:i:s'), 
            ]);
        }

        public function removeBookmarkNotification(int $receiver, int $post_id) {
            $user_id = Session::get(USER_SESSION_NAME);
            return $this->delete('type = ? AND sender = ? AND receiver = ? AND post_id = ?', 
                                    ['Bookmark', $user_id, $receiver, $post_id]);
        }

        public function addCommentNotification(int $receiver, int $post_id, int $comment_id) {
            return $this->insert([
                'type' => 'Comment', 
                "sender" => Session::get(USER_SESSION_NAME), 
                "receiver" => $receiver, 
                "post_id" => $post_id,
                'comment_id' => $comment_id,
                "created_at" => date('Y-m-d H:i:s'), 
            ]);
        }

        public function removeCommentNotification(int $receiver, int $comment_id) {
            $user_id = Session::get(USER_SESSION_NAME);
            return $this->delete('type = ? AND sender = ? AND receiver = ? AND comment_id = ?', 
                                    ['Comment', $user_id, $receiver, $comment_id]);
        }

        public function addSubCommentNotification(int $receiver, int $post_id, int $comment_id, int $sub_comment_id) {
            return $this->insert([
                'type' => 'SubComment', 
                "sender" => Session::get(USER_SESSION_NAME), 
                "receiver" => $receiver, 
                "post_id" => $post_id,
                'comment_id' => $comment_id,
                'sub_comment_id' => $sub_comment_id,
                "created_at" => date('Y-m-d H:i:s'), 
            ]);
        }

        public function removeSubCommentNotification(int $receiver, int $sub_comment_id) {
            $user_id = Session::get(USER_SESSION_NAME);
            return $this->delete('type = ? AND sender = ? AND receiver = ? AND sub_comment_id = ?', 
                                    ['SubComment', $user_id, $receiver, $sub_comment_id]);
        }

        public function getNotificationCount() {
            $sql = "SELECT COUNT(receiver) AS counts FROM notifications WHERE status = 0 AND receiver = ?";
            return $this->query($sql, [Session::get(USER_SESSION_NAME)])->getResult()[0]->counts;
        }

        public function fetchNotification() {
            $sql = "SELECT notifications.notification_id, notifications.type, notifications.receiver, notifications.sender, 
                            notifications.post_id, notifications.comment_id, notifications.sub_comment_id, notifications.status, 
                            notifications.created_at, users.username, users.user_id, posts.post_id, posts.post_title
                    FROM notifications 
                    LEFT JOIN users
                    ON notifications.sender = users.user_id
                    LEFT JOIN posts
                    ON notifications.post_id = posts.post_id
                    WHERE receiver = ? ORDER BY notification_id ASC LIMIT 15";
            return $this->query($sql, [Session::get(USER_SESSION_NAME)])->getResult();
        }

        public function updateNotificationStatus() {
            return $this->update('receiver = ? AND status = ?', [Session::get(USER_SESSION_NAME), 0], ['status' => 1]);
        }

    }
?>