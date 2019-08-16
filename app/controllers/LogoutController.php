<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Router;

    class LogoutController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->view->setLayout('default');
        }

        public function indexAction() {
            if(Session::exists(USER_SESSION_NAME)) {
                $this->UsersModel->logout();
                Router::redirect('login');
            }
        }
    }
?> 