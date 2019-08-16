<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;

    class FollowController extends Controller {
        private $follow_id, $follower, $following, $created_at;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Follow');

            $this->APIheaders();
        }

        public function followAction(int $user_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            if($user_id == Session::get(USER_SESSION_NAME)) die();

            $result = $this->FollowModel->follow($user_id);
            if($result == "followed" || $result == "unfollowed") {
                $follower_count = $this->FollowModel->getFollowingCount(Session::get(USER_SESSION_NAME));
                echo json_encode(['status' => $result, 'followerCount' => $follower_count]);
            }
        }

    }

?>