<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class Bookmarks extends Model {
        public $bookmark_id, $post_id, $user_id ;

        public function __construct() {
            parent::__construct('bookmarks');
        }

        public function insertBookmark(int $post_id, int $user_id) {
            $n = new Notifications();
            $result = $this->findFirst(['conditions'=>'user_id = ? AND post_id = ?', 
                                        'bind'=>[Session::get(USER_SESSION_NAME), $post_id]]);
            
            if(!empty($result)) {
                if(Session::get(USER_SESSION_NAME) != $user_id) $n->removeBookmarkNotification($user_id, $post_id);
                return $this->delete('post_id = ? AND user_id = ?', [$post_id, Session::get(USER_SESSION_NAME)]);
            } else {
                if(Session::get(USER_SESSION_NAME) != $user_id) $n->addBookmarkNotification($user_id, $post_id);
                return $this->insert([
                    'user_id' => Session::get(USER_SESSION_NAME),
                    'post_id' => $post_id
                ]);
            }
        }

        public function getBookmarkCount(int $user_id) {
            $sql = "SELECT count(post_id) as c FROM bookmarks WHERE user_id = $user_id";
            return $this->query($sql)->getResult()[0]->c;
        }

        public function fetchBookmarkedPosts(int $user_id, int $start_position = 0) {
            $sql = "SELECT posts.post_id, posts.post_int, posts.post_title, posts.main_post, posts.post_type,
                    posts.post_comments, posts.post_image, posts.post_promotes, posts.created_at,
                    posts.user_id, users.username, users.profile_image,
                    posts.option_one, posts.option_two, posts.option_three, posts.option_four,
                    posts.duration, posts.option_one_count, posts.option_two_count,
                    posts.option_three_count, posts.option_four_count, post_promotes.status,
                    bookmarks.bookmark_id FROM posts
                    LEFT JOIN users
                    ON posts.user_id = users.user_id
                    LEFT JOIN post_promotes
                    ON posts.post_id = post_promotes.post_id
                    AND post_promotes.user_id = $user_id
                    LEFT JOIN bookmarks
                    ON posts.post_id = bookmarks.post_id
                    AND bookmarks.user_id = $user_id
                    WHERE posts.post_id IN (SELECT post_id FROM bookmarks WHERE user_id = $user_id) AND isDeleted <> 1
                    ORDER BY posts.post_id DESC LIMIT $start_position, 10";
                    
            return $this->query($sql)->getResult();
        }

    }
?>