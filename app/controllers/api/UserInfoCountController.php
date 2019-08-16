<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Interests;

    class UserInfoCountController extends Controller {
        private $user_id, $user;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Posts');
            $this->loadModel('Bookmarks');
            $this->loadModel('Follow');
            $this->loadModel('Interests');
            $this->loadModel('Notifications');

            $this->APIheaders();
        }

        public function indexAction() {
            $user = $this->UsersModel->findFirst(["conditions"=>'user_id = ?', "bind"=>[Session::get(USER_SESSION_NAME)]]);
            $user_interests = $this->InterestsModel->fetchInterestCount(Session::get(USER_SESSION_NAME));
            $bookmark_count = $this->BookmarksModel->getBookmarkCount(Session::get(USER_SESSION_NAME));
            $post_count = $this->PostsModel->getPostCreatedByUserCount(Session::get(USER_SESSION_NAME));
            $follower_count = $this->FollowModel->getFollowersCount(Session::get(USER_SESSION_NAME));
            $following_count = $this->FollowModel->getFollowingCount(Session::get(USER_SESSION_NAME));
            
            echo json_encode([
                    'loggedUser' => $user, 
                    'userInterests' => $user_interests, 
                    'bookmarkCount' => $bookmark_count, 
                    'postCount' => $post_count, 
                    'followerCount' => $follower_count, 
                    'followingCount' => $following_count
                ]);
        }

    }