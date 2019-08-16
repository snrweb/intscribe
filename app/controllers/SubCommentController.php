<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Validation;

    class SubCommentController extends Controller {
        private $post_id;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Posts');
            $this->loadModel('Comments');
            $this->loadModel('SubComments');
            $this->loadModel('Notifications');

            $this->view->sub_comment_error = $this->view->csrf_token_error = '';
        }

        public function addAction(int $post_id, int $comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $result = $this->CommentsModel->findFirst(['conditions'=>'comment_id = ?', 'bind'=>[$comment_id]]);
            $this->view->ref = $result->comment;
            $this->view->post_id = $post_id;
            $this->view->comment_id = $comment_id;
            $validate = new Validation();
            
            if($_POST) {
                $this->SubCommentsModel->post_id = $post_id;
                $this->SubCommentsModel->comment_id = $comment_id;
                $this->SubCommentsModel->assign($_POST);
                $validate->check('$_POST', ['sub_comment'=>['display'=>'Comment', 'required'=>true]], true);
                if($validate->passed()) {
                    if($this->SubCommentsModel->insertSubComment($result->user_id)) {
                        $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$result->post_id]]);
                        Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$post_id);
                    }
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('editors/add_scomment');
        }
        
        public function editAction(int $sub_comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $result = $this->SubCommentsModel->findFirst([
                                'conditions'=>'sub_comment_id = ? AND user_id = ?', 
                                'bind'=>[$sub_comment_id, Session::get(USER_SESSION_NAME)]
                            ]);

            $this->view->sub_comment_id = $sub_comment_id;
            $this->view->ref = $this->view->sub_comment = $result->sub_comment;
            
            if($_POST) {
                $validate = new Validation();
                $this->SubCommentsModel->sub_comment_id = $sub_comment_id;
                $this->SubCommentsModel->assign($_POST);
                $validate->check('$_POST', ['sub_comment'=>['display'=>'Comment', 'required'=>true]], true);
                if($validate->passed()) {
                    $this->SubCommentsModel->updateSubComment();
                    $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$result->post_id]]);
                    Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$res->post_id);
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('editors/edit_scomment');
        }

        public function deleteAction(int $post_id, int $sub_comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->SubCommentsModel->sub_comment_id = $sub_comment_id;
            if($this->SubCommentsModel->deleteSubComment()) {
                $resSC = $this->SubCommentsModel->findFirst(['conditions'=>'sub_comment_id = ?', 'bind'=>[$sub_comment_id]]);
                $resC = $this->CommentsModel->findFirst(['conditions'=>'comment_id = ?', 'bind'=>[$resSC->comment_id]]);
                if(Session::get(USER_SESSION_NAME) != $resC->user_id) {
                    $this->NotificationsModel->removeSubCommentNotification($resC->user_id, $sub_comment_id); 
                }

                $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
                Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$res->post_id);
            }
        }

    }

?>