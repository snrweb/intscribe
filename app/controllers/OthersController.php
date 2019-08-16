<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Validation;
    use Core\Session;

    class OthersController extends Controller {
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Admin');
            $this->loadModel('Users');
            $this->loadModel('Notifications');

            $this->APIheaders();
        }

        public function generalSearch($search) {
            $users = $this->UsersModel->searchUser($search);
            $posts = $this->PostsModel->searchPost($search);
        }

        public function termsAction() {
            
        }

        public function privacyAction() {
            
        }

        public function aboutAction() {
            
        }
    }