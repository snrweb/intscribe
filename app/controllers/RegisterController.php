<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Router;
    use Core\Sanitise;
    use Core\Validation;

    class RegisterController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->view->setLayout('default');
        }
        
        public function indexAction() {
            //Error variables initialisation
            $this->view->email_error = $this->view->username_error = $this->view->password_error = '';
            $this->view->csrf_token_error = $this->view->registrationError = '';;

            //Initialise these view properties used in the registration form
            $this->view->email = $this->view->username = '';

            if($_POST) {

                /**Assigns the value of $_POST properties to the properties of the 
                 * UsersModel object*/
                $this->UsersModel->assign($_POST);
                foreach($_POST as $key => $value) {
                    $this->view->$key = $this->UsersModel->$key;
                }

                //instantiate the Validation class
                $validate = new Validation();
                $validate->check('$_POST', $this->UsersModel->validateRegistration(), true);
                
                if($validate->passed()) {
                    $password = Sanitise::get('password');
                    $confirm_password = Sanitise::get('confirm_password');

                    if ($password === $confirm_password) {
                        $this->UsersModel->password = password_hash($password, PASSWORD_DEFAULT);

                        $registered = $this->UsersModel->register(); 

                        $error = '<span class="inputError">Registration error</span>';
                        ($registered) ? Router::redirect('login') : $this->view->registrationError = $error;
                    } else {
                        $this->view->password_error = '<span class="inputError">Password does not match</span>';
                    }

                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('register/register');
        }
    }

?>