<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Interests;
    use Core\Router;

    class NotificationController extends Controller {
        private $interest_name;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Posts');
            $this->loadModel('Interests');
            $this->loadModel('Notifications');

            $this->view->page_name = 'notification';
        }

        public function paramsAction() {
            if(Session::exists(USER_SESSION_NAME)) usersInfo($this->view);
            $notifications = $this->NotificationsModel->fetchNotification();
            $this->NotificationsModel->updateNotificationStatus();

            $this->view->read = [];
            $this->view->unread = [];

            foreach($notifications as $n) {
                if($n->status == 0) {
                    array_push($this->view->read, $n);
                } else {
                    array_push($this->view->unread, $n);
                }
            }

            $this->view->interests = Interests::getInterests();
            $this->view->posts = $this->PostsModel->fetchInterestBasedPosts($this->interest_name);;
            $this->view->render('notification/notification');
        }

    }
?>