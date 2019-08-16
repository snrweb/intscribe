<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Validation;
    use Core\Session;
    use Core\Router;
    use Core\CSRF;

    class ReportController extends Controller {
        private $res;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Reports');
            $this->loadModel('Posts');
            $this->loadModel('Comments');
            $this->loadModel('SubComments');

            $this->view->report = $this->view->report_error = $this->view->csrf_token_error = '';
        }

        private function setRef(string $type, int $post_id, int $comment_id, int $subcomment_id) {
            $this->res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
            if($type == 'Post') {
                $this->view->ref = $this->res->post_title;
            } elseif ($type == 'Comment') {
                $result = $this->CommentsModel->findFirst(['conditions'=>'comment_id = ?', 'bind'=>[$comment_id]]);
                $this->view->ref = $result->comment;
            } elseif ($type == 'SubComment') {
                $result = $this->SubCommentsModel->findFirst(['conditions'=>'sub_comment_id = ?', 'bind'=>[$subcomment_id]]);
                $this->view->ref = $result->sub_comment;
            }
        }

        public function reportAction(string $type, int $post_id, int $comment_id = 0, int $subcomment_id = 0) {
            if(Session::exists(USER_SESSION_NAME)) {
                $this->setRef($type, $post_id, $comment_id, $subcomment_id);
                
                $this->view->report_tag = $this->ReportsModel->report_tag = $type;
                $this->view->post_id = $this->ReportsModel->post_id = $post_id;
                $this->view->comment_id = $this->ReportsModel->comment_id = $comment_id;
                $this->view->subcomment_id = $this->ReportsModel->subcomment_id = $subcomment_id;

                if($_POST) {
                    $validate->check('$_POST', ['report' => ['display'=>'Report', 'required'=> true]], true);
                    $this->ReportsModel->assign($_POST);
                    if($validate->passed()) {
                        if($this->ReportsModel->insertReport()) {
                            Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$post_id);
                        }
                    } else {
                        $this->view->setFormErrors($validate->getErrors());
                    }
                }
                $this->view->render('editors/report');
            } else {
                Router::redirect('login');
            }
        }

        
    }