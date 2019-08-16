<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Interests;
    use Core\Router;

    class HomeController extends Controller {
        
        /********************
         * Call the extended controller construct to 
         * instatiate the view object
         */
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Posts');
            $this->loadModel('Interests');
            $this->loadModel('Bookmarks');
            $this->loadModel('Follow');

            $this->view->page_name = '';
        }

        /***********
         * The default action if no action is provided
         */
        public function indexAction() {
            $this->view->interests = Interests::getInterests();
            if(Session::exists(USER_SESSION_NAME)) {
                $this->view->bookmark_pg = 'home';
                usersInfo($this->view);
            }

            $posts = $this->PostsModel->fetchAllPosts();

            if(Session::exists(USER_SESSION_NAME)) {
                if(count($posts) < 10) {
                    $otherPost = $this->PostsModel->fetchOtherPosts();
                    foreach($otherPost as $o) {
                        $count = 0;
                        foreach($posts as $p) {
                            if($o->post_title == $p->post_title) {
                                $count = 1;
                            }
                        }
                        if($count == 0) {
                            array_push($posts, $o);
                        }
                    }
                }
            }
            $this->view->posts = $posts;
            $this->view->render('home/home');
        }
    }
?>