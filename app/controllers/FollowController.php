<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Router;

    class FollowController extends Controller {
        private $follow_id, $follower, $following, $created_at;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Follow');
            $this->loadModel('Posts');

            $this->view->setLayout('default');
            $this->view->page_name = 'post';
        }

        public function pfollowAction(int $post_id, int $user_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('');
            if($user_id == Session::get(USER_SESSION_NAME)) Router::redirect('');

            $result = $this->FollowModel->follow($user_id);
            if($result == "followed" || $result == "unfollowed") {
                $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
                Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$res->post_id);
            }
        }

        // this enables the sessional user to follow
        // the clicked user and 
        // then redirect to the sessional users page
        public function ufollowAction(int $user_id, string $page) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('');
            if($user_id == Session::get(USER_SESSION_NAME)) Router::redirect('');

            $result = $this->FollowModel->follow($user_id);
            if($result == "followed" || $result == "unfollowed") {
                $res = $this->UsersModel->findFirst(['conditions'=>'user_id = ?', 'bind'=>[Session::get(USER_SESSION_NAME)]]);
                Router::redirect('user/'.str_replace([" "], ["-"], $res->username).'-'.$res->user_id.'/'.$page);
            }
        }

        // this enables the sessional user to follow
        // the clicked user and 
        // then redirect to the followed user's page
        public function mfollowAction(int $user_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('');
            if($user_id == Session::get(USER_SESSION_NAME)) Router::redirect('');

            $result = $this->FollowModel->follow($user_id);
            if($result == "followed" || $result == "unfollowed") {
                $res = $this->UsersModel->findFirst(['conditions'=>'user_id = ?', 'bind'=>[$user_id]]);
                Router::redirect('user/'.str_replace([" "], ["-"], $res->username).'-'.$res->user_id);
            }
        }

    }

?>