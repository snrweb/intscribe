<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Interests;
    use Core\Router;

    class InterestController extends Controller {
        private $interest_name;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Posts');
            $this->loadModel('Interests');
            $this->loadModel('Bookmarks');
            $this->loadModel('Follow');

            $this->interest_name = str_replace(["Action", "-"], ["", " "], $action);
            $this->view->page_name = 'interest';
        }

        public function paramsAction() {
            $this->view->interests = Interests::getInterests();
            if(Session::exists(USER_SESSION_NAME)) usersInfo($this->view);

            $this->view->posts = $this->PostsModel->fetchInterestBasedPosts($this->interest_name);
            $this->view->render('home/home');
        }

        public function addInterestAction(string $interest_name, int $user_id = 0) {
            if(Session::exists(USER_SESSION_NAME)) {
                $this->InterestsModel->interest = str_replace("-", " ", $interest_name);
                $this->InterestsModel->insertInterest();
                if($user_id != 0) {
                    $u = $this->UsersModel->findFirst(["conditions"=>'user_id = ?', "bind"=>[$user_id]]);
                    Router::redirect('user/'.str_replace(" ", "-", $u->username).'-'.$user_id.'/interest');
                } else {
                    Router::redirect('');
                }
            }
            Router::redirect('');
        }
    }
?>