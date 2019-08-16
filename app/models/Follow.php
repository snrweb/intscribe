<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class Follow extends Model {
        public $follow_id, $follower, $following, $created_at;

        public function __construct() {
            parent::__construct('follow');
        }

        private function unFollow($user_id) {
            return $this->delete('follower = ? AND following = ?', [Session::get(USER_SESSION_NAME), $user_id]);
        }


        public function follow(int $user_id) {
            $n = new Notifications();
            $result = $this->findFirst(['conditions'=>'follower = ? AND following = ?', 
                                        'bind'=>[Session::get(USER_SESSION_NAME), $user_id]]);
            if(!empty($result)) {
                $n->removeFollowNotification($user_id);
                if($this->unFollow($user_id)) return "unfollowed";
            } else {
                if($this->insert([
                    'following' => $user_id, 
                    'follower' => Session::get(USER_SESSION_NAME),
                    'created_at' => date('Y-m-d H:i:s')
                ])) {
                    $n->addFollowNotification($user_id);
                    return "followed";
                }
            }
        }

        // fetch those the @user_id is following
        // from the database
        public function fetchFollowers(int $user_id, int $start = 0) {
            $sql = "SELECT follow.follower, follow.created_at, users.profile_image, users.username, users.user_id 
                    FROM follow
                    LEFT JOIN users
                    ON follow.follower = users.user_id
                    WHERE following = ? LIMIT $start, 10";
            return $this->query($sql, [$user_id])->getResult();
        }

        public function getFollowersCount($user_id) {
            $sql = "SELECT count(follower) as c FROM follow WHERE following = ?";
            return $this->query($sql, [$user_id])->getResult()[0]->c;
        }

        public function fetchLiteFollowers($user_id) {
            $sql = "SELECT follow.follower FROM follow WHERE following = ? LIMIT 30";
            return $this->query($sql, [$user_id])->getResult();
        }

        // fetch those following the @user_id 
        // from the database
        public function fetchFollowing($user_id, int $start = 0) {
            $sql = "SELECT follow.following, follow.created_at, users.profile_image, users.username, users.user_id 
                    FROM follow
                    LEFT JOIN users
                    ON follow.following = users.user_id
                    WHERE follower = ? LIMIT $start, 10";
            return $this->query($sql, [$user_id])->getResult();
        }

        public function getFollowingCount($user_id) {
            $sql = "SELECT count(following) as c FROM follow WHERE follower = ?";
            return $this->query($sql, [$user_id])->getResult()[0]->c;
        }

        // fetch those following the @user_id 
        // from the database
        public function fetchLiteFollowing() {
            $sql = "SELECT follow.following FROM follow WHERE follower = ?";
            $res = $this->query($sql, [Session::get(USER_SESSION_NAME)])->getResult();
            $followings = [];
            foreach($res as $f) {
                array_push($followings, $f->following);
            }
            return $followings;
        }

    }
?>