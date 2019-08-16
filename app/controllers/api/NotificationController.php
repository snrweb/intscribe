<?php 
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Interests;
    use Core\Router;

    class NotificationController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Posts');
            $this->loadModel('Interests');
            $this->loadModel('Notifications');
            
            $this->APIheaders();
        }

        public function indexAction() {
            $notifications = $this->NotificationsModel->fetchNotification();
            $this->NotificationsModel->updateNotificationStatus();

            $read = [];
            $unread = [];

            foreach($notifications as $n) {
                if($n->status == 0) {
                    array_push($unread, $n);
                } else {
                    array_push($read, $n);
                }
            }

           echo json_encode(['read' => $read, 'unread' => $unread]);
        }

    }
?>