<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Router;

    class NavController extends Controller {
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

        public function userInfoAction() {
            $view->user = $UsersModel->findFirst(["conditions"=>'user_id = ?', "bind"=>[Session::get(USER_SESSION_NAME)]]);
            $view->user_interests = $this->InterestsModel->fetchInterests(Session::get(USER_SESSION_NAME));
            $view->interest_count = $this->InterestsModel->fetchInterestCount(Session::get(USER_SESSION_NAME));
            $view->bookmark_count = $this->BookmarksModel->getBookmarkCount(Session::get(USER_SESSION_NAME));
            $view->post_count = $this->PostsModel->getPostCreatedByUserCount(Session::get(USER_SESSION_NAME));
            $view->follower_count = $this->FollowModel->getFollowersCount(Session::get(USER_SESSION_NAME));
            $view->following_count = $this->FollowModel->getFollowingCount(Session::get(USER_SESSION_NAME));
        }

        public function indexAction() {
            $user = $this->UsersModel->findFirst(["conditions"=>'user_id = ?', "bind"=>[Session::get(USER_SESSION_NAME)]]);
            $notif_count = $this->NotificationsModel->getNotificationCount();
            Session::set('notif_count', $notif_count);
            echo json_encode(['loggedUser' => $user, 'notificationCount' => $notif_count]);
        }

    }