<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Validation;

    class AdminController extends Controller {
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
        }

        public function editAboutAction() {
            $this->view->urlParams = 'about';
            $this->view->csrf_token_error = '';

            if($_POST) {
                $this->AdminModel->assign($_POST);

                $validate = new Validation();
                $validate->check('$_POST', [], true);
                
                if($validate->passed()) {
                    $updated = $this->AdminModel->updateAbout(); 
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('admin/aboutTerms');
        }

        public function editTermsAction() {
            $this->view->urlParams = 'terms';
            $this->view->csrf_token_error = '';

            if($_POST) {
                $this->AdminModel->assign($_POST);

                $validate = new Validation();
                $validate->check('$_POST', [], true);
                
                if($validate->passed()) {
                    $updated = $this->AdminModel->updateTerms(); 
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('admin/aboutTerms');
        }
    }