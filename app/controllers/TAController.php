<?php 
    namespace App\Controllers;
    use Core\Controller;

    class TAController extends Controller {
        
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);

            $this->loadModel('Admin');
        }

        public function indexAction() {
            $this->view->terms = $this->AdminModel->getTerms();
            $this->view->render('home/terms');
        }

        public function termsAction() {
            $this->view->terms = $this->AdminModel->getTerms();
            $this->view->render('home/terms');
        }

        public function aboutAction() {
            $this->view->about = $this->AdminModel->getAbout();
            $this->view->render('home/about');
        }
    }
?>