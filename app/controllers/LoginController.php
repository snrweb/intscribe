<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Router;
    use Core\Sanitise;
    use Core\Validation;
    use Core\Session;
    use Core\CSRF;

    class LoginController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('admin223');
            $this->view->setLayout('default');
            
            $this->view->email = $this->view->password = '';
            $this->view->email_error = $this->view->csrf_token_error = $this->view->password_error = '';
        }

        public function indexAction() {
            /** validate input*/
            if ($_POST) {
                $validate = new Validation();
                $validate->check('$_POST', [
                    'email' => ['display' => 'Email', 'required' => true, 'isEmail' => true],
                    'password' => ['display' => 'Password', 'required' => true]
                ], true);

                /**Assign the input to values to the corresponding Buyer class properties **/
                $this->UsersModel->assign($_POST);
                
                /**login user if vaidation is passed,
                 * user exist in the database and 
                 * password is correct**/
                if($validate->passed()) {
                    $user = $this->UsersModel->finduser();
                    
                    if(count($user)) {
                        if(password_verify($this->UsersModel->password, $user[0]->password)) {
                            $rememberMe = (isset($_POST['remember_me']) && Sanitise::get('remember_me')) ? true : false;

                            $this->UsersModel->user_id = $user[0]->user_id; 
                            $this->UsersModel->login($rememberMe);
                            
                            Router::redirect('');
                        } else {
                            $this->view->password_error = '<span class="inputError">Password is incorrect</span>'; 
                        }
                    } else {
                        $this->view->email_error = '<span class="inputError">This user does not exist in the database</span>';
                    }
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }

            $this->view->render('login/login');
        }

        
        public function adminLoginAction() {
            $this->view->csrf_token_error = '';
            if ($_POST) {
                $validate = new Validation();
                $validate->check('$_POST', [
                    'admin_password' => ['display' => 'Password', 'required' => true]
                ], true);
                if($validate->passed()) {
                    $result = $this->admin223Model->findFirst(['conditions'=>'admin_username = ?', 'bind'=>[$_POST['admin_username']]]);
                    if(password_verify($_POST['admin_password'], $result->admin_password)) {
                        $this->admin223Model->admin_id = $result->admin_id; 
                        $this->admin223Model->admin_username = $result->admin_username; 
                        $this->admin223Model->login();
                        Router::redirect('admin223/');
                    } else {
                        Router::redirect('');
                    }
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('admin/login');
        }
    }
?> 