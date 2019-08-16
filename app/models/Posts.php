<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class Posts extends Model {
        public $post_id, $post_type, $post_title, $main_post, $user_id, $post_int, $post_image, $option_one,
        $option_two, $option_three = '', $option_four = '', $post_comments, $post_promotes, $views, $question_link,
        $created_at, $isDeleted, $isJSeditor = 0;

        public function __construct() {
            parent::__construct('posts');
        }

        public function insertPost() {
            if($this->post_type == 'Article') {
                return $this->insert([
                    "post_title" => $this->post_title, 
                    "main_post" => html_entity_decode($this->main_post), 
                    'post_type' => $this->post_type,
                    "created_at" => date('Y-m-d H:i:s'), 
                    "user_id" => Session::get(USER_SESSION_NAME), 
                    "post_int" => $this->post_int,
                    'isJSeditor' => $this->isJSeditor
                ]);
            } 
            
            if($this->post_type == 'Question') {
                return $this->insert([
                    "post_title" => $this->post_title, 
                    'post_type' => $this->post_type,
                    "created_at" => date('Y-m-d H:i:s'), 
                    "user_id" => Session::get(USER_SESSION_NAME), 
                    "post_int" => $this->post_int,
                    "question_link" => $this->question_link
                ]);
            } 
            
            if($this->post_type == 'Poll') {
                $poll_min = $_POST['poll-editor-mins']; 
                $poll_hour = $_POST['poll-editor-hours'];
                $poll_day = $_POST['poll-editor-days']; 
                
                $duration = strtotime("+$poll_day days $poll_hour hours $poll_min minutes");
                $duration = date('Y-m-d H:i:s', $duration);

                return $this->insert([
                    "post_title" => $this->post_title, 
                    'post_type' => $this->post_type,
                    "created_at" => date('Y-m-d H:i:s'), 
                    "user_id" => Session::get(USER_SESSION_NAME), 
                    "post_int" => $this->post_int,
                    'option_one' => $this->option_one, 
                    'option_two' => $this->option_two, 
                    'option_three' => $this->option_three, 
                    'option_four' => $this->option_four, 
                    'duration' => $duration
                ]);
            }
        }

        public function updatePost() {
            if($this->post_type == 'Article') {
                return $this->update('post_id = ? AND user_id = ?', [$this->post_id, Session::get(USER_SESSION_NAME)], [
                    'post_title' => $this->post_title,
                    "main_post" => html_entity_decode($this->main_post), 
                    'post_int' =>$this->post_int
                ]);
            }

            if($this->post_type == 'Question') {
                return $this->update('post_id = ? AND user_id = ?', [$this->post_id, Session::get(USER_SESSION_NAME)], [
                    'post_title' => $this->post_title,
                    'post_int' =>$this->post_int
                ]);
            }
        }

        public function fetchMainPost($post_id) {
            if(Session::exists(USER_SESSION_NAME)) {
                $user = Session::get(USER_SESSION_NAME);
                $sql = "SELECT posts.post_id, posts.post_int, posts.post_image, posts.post_title, posts.main_post, posts.views,
                        posts.post_promotes, posts.created_at, posts.user_id, posts.question_link,
                        posts.post_type, users.username, users.profile_image, post_promotes.status,
                        bookmarks.bookmark_id, follow.follow_id
                        FROM posts
                        LEFT JOIN users
                        ON posts.user_id = users.user_id
                        LEFT JOIN post_promotes
                        ON posts.post_id = post_promotes.post_id
                        AND post_promotes.user_id = $user
                        LEFT JOIN bookmarks
                        ON posts.post_id = bookmarks.post_id
                        AND bookmarks.user_id = $user
                        LEFT JOIN follow
                        ON posts.user_id = follow.following
                        AND follow.follower = $user
                        WHERE posts.post_id = ? AND isDeleted <> 1";
                return $this->query($sql, [$post_id])->getResult()[0];
            } else {
                $sql = "SELECT posts.post_id, posts.post_int, posts.post_image, posts.post_title, posts.main_post, posts.views,
                        posts.post_promotes, posts.created_at, posts.user_id, posts.post_type, posts.question_link,
                        users.profile_image, users.username
                        FROM posts
                        LEFT JOIN users
                        ON posts.user_id = users.user_id
                        WHERE posts.post_id = ? AND isDeleted <> 1";
                return $this->query($sql, [$post_id])->getResult()[0];
            }
        }
        
        public function fetchAllPosts($start_position = 0) {
            if(Session::exists(USER_SESSION_NAME)) {
                $user = Session::get(USER_SESSION_NAME);
                $sql = "SELECT posts.post_id, posts.post_int, posts.post_title, posts.main_post, posts.post_type,
                        posts.post_comments, posts.post_image, posts.post_promotes, posts.created_at, posts.question_link,
                        users.user_id, users.username, users.profile_image,
                        posts.option_one, posts.option_two, posts.option_three, posts.option_four,
                        posts.duration, posts.option_one_count, posts.option_two_count,
                        posts.option_three_count, posts.option_four_count, post_promotes.status,
                        bookmarks.bookmark_id FROM posts
                        LEFT JOIN interests
                        ON posts.post_int = interests.interest
                        LEFT JOIN users
                        ON posts.user_id = users.user_id
                        LEFT JOIN post_promotes
                        ON posts.post_id = post_promotes.post_id
                        AND post_promotes.user_id = $user
                        LEFT JOIN bookmarks
                        ON posts.post_id = bookmarks.post_id
                        AND bookmarks.user_id = $user
                        WHERE interests.user_id =  $user AND isDeleted <> 1
                        ORDER BY created_at DESC, posts.post_promotes DESC LIMIT $start_position, 10";
                return $this->query($sql)->getResult();
            } else {
                $sql = "SELECT posts.post_id, posts.post_int, posts.post_title, posts.main_post, posts.post_type,
                        posts.post_comments, posts.post_image, posts.post_promotes, posts.created_at, posts.question_link,
                        users.user_id, users.username, users.profile_image,
                        posts.option_one, posts.option_two, posts.option_three, posts.option_four,
                        posts.duration, posts.option_one_count, posts.option_two_count,
                        posts.option_three_count, posts.option_four_count FROM posts
                        LEFT JOIN users
                        ON posts.user_id = users.user_id
                        WHERE isDeleted <> 1
                        ORDER BY created_at DESC, posts.post_promotes DESC LIMIT $start_position, 10";
                return $this->query($sql)->getResult();
            }
        }

        public function fetchOtherPosts($start_position = 0) {
            if(Session::exists(USER_SESSION_NAME)) {
                $user = Session::get(USER_SESSION_NAME);
                $sql = "SELECT posts.post_id, posts.post_int, posts.post_title, posts.main_post, posts.post_type,
                        posts.post_comments, posts.post_image, posts.post_promotes, posts.created_at, posts.question_link,
                        users.user_id, users.username, users.profile_image,
                        posts.option_one, posts.option_two, posts.option_three, posts.option_four,
                        posts.duration, posts.option_one_count, posts.option_two_count,
                        posts.option_three_count, posts.option_four_count, post_promotes.status,
                        bookmarks.bookmark_id FROM posts
                        LEFT JOIN users
                        ON posts.user_id = users.user_id
                        LEFT JOIN post_promotes
                        ON posts.post_id = post_promotes.post_id
                        AND post_promotes.user_id = $user
                        LEFT JOIN bookmarks
                        ON posts.post_id = bookmarks.post_id
                        AND bookmarks.user_id = $user
                        WHERE isDeleted <> 1 AND post_int NOT IN (SELECT interest FROM interests WHERE user_id = ?)
                        ORDER BY created_at DESC, posts.post_promotes DESC LIMIT $start_position, 10";
                return $this->query($sql, [Session::get(USER_SESSION_NAME)])->getResult();
            }
        }

        public function fetchInterestBasedPosts($interest_name, $start_position = 0) {
            $sql;
			$interest_name = str_replace('-', ' ', $interest_name);
            if(Session::exists(USER_SESSION_NAME)) {
                $user_id = Session::get(USER_SESSION_NAME);
                $sql = "SELECT posts.post_id, posts.post_int, posts.post_title, posts.main_post, posts.post_type,
                        posts.post_comments, posts.post_image, posts.post_promotes, posts.created_at, posts.question_link,
                        users.user_id, users.username, users.profile_image,
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
                        WHERE posts.post_int = ? AND isDeleted <> 1
					    ORDER BY created_at DESC, posts.post_promotes DESC LIMIT $start_position, 10";
            } else {
                $sql = "SELECT posts.post_id, posts.post_int, posts.post_title, posts.main_post, posts.post_type,
                        posts.post_comments, posts.post_image, posts.post_promotes, posts.created_at, posts.question_link,
						posts.user_id, users.username, users.profile_image,
                        posts.option_one, posts.option_two, posts.option_three, posts.option_four,
						posts.duration, posts.option_one_count, posts.option_two_count,
						posts.option_three_count, posts.option_four_count FROM posts
						LEFT JOIN users
                        ON posts.user_id = users.user_id
                        WHERE posts.post_int = ? AND isDeleted <> 1
					    ORDER BY created_at DESC, posts.post_promotes DESC LIMIT $start_position, 10";
            }
            return $this->query($sql, [$interest_name])->getResult();
        }

        public function fetchAllPostCreatedByUser(int $user_id, int $start_position = 0) {
            $sql = "SELECT posts.post_id, posts.post_int, posts.post_title, posts.main_post, posts.post_type,
					posts.post_comments, posts.post_image, posts.post_promotes, posts.created_at, posts.question_link,
					posts.user_id, users.username, users.profile_image,
					posts.option_one, posts.option_two, posts.option_three, posts.option_four,
					posts.duration, posts.option_one_count, posts.option_two_count,
					posts.option_three_count, posts.option_four_count, post_promotes.status,
					bookmarks.bookmark_id, follow.follow_id FROM posts
					LEFT JOIN users
					ON posts.user_id = users.user_id
                    LEFT JOIN post_promotes
                    ON posts.post_id = post_promotes.post_id
                    AND post_promotes.user_id = $user_id
                    LEFT JOIN bookmarks
                    ON posts.post_id = bookmarks.post_id
                    AND bookmarks.user_id = $user_id
                    LEFT JOIN follow
                    ON posts.user_id = follow.following
                    AND follow.follower = $user_id
                    WHERE posts.user_id = $user_id AND isDeleted <> 1
                    ORDER BY posts.post_id DESC, posts.post_promotes DESC LIMIT $start_position, 10";
            return $this->query($sql)->getResult();
        }

        public function getPostCreatedByUserCount(int $user_id) {
            $sql = "SELECT count(post_id) as c FROM posts WHERE user_id = $user_id AND isDeleted <> 1";
            return $this->query($sql)->getResult()[0]->c;
        }

        public function softDeletePost(int $post_id) {
            if($this->update('post_id = ? AND user_id = ?', 
                            [$post_id, Session::get(USER_SESSION_NAME)], 
                            ['isDeleted' => 1])) {

                $this->query("DELETE FROM bookmarks WHERE post_id = ?", [$post_id])->getResult();
                return true;
            }
        }

        public function searchPost($search) {
            $sql = "SELECT post_id, post_title FROM posts WHERE isDeleted <> 1 AND post_title LIKE '%".$search."%' LIMIT 10";
            return $this->query($sql)->getResult();
        }
    }

?>