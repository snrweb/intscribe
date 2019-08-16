<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Validation;

    class CommentController extends Controller {
        private $post_id;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Posts');
            $this->loadModel('Comments');
            $this->loadModel('CommentPromotes');
            $this->loadModel('Notifications');

            $this->view->comment_error = $this->view->csrf_token_error = '';
        }

        public function addAction(int $post_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $result = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
            $this->view->ref = $result->post_title;
            $this->view->post_id = $post_id;
            $validate = new Validation();
            
            if($_POST) {
                $this->CommentsModel->post_id = $post_id;
                $this->CommentsModel->assign($_POST);
                $validate->check('$_POST', ['comment'=>['display'=>'Comment', 'required'=>true]], true);
                if($validate->passed()) {
                    if($this->CommentsModel->insertComment($result->user_id)) {
                        Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $result->post_title).'-'.$post_id);
                    }
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('editors/add_comment');
        }
        
        public function editAction(int $comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $result = $this->CommentsModel->findFirst([
                                'conditions'=>'comment_id = ? AND user_id = ?', 
                                'bind'=>[$comment_id, Session::get(USER_SESSION_NAME)]
                            ]);

            $this->view->ref = $result->comment;
            $this->view->comment_id = $comment_id;
            $this->view->comment = $result->comment;
            
            if($_POST) {
                $validate = new Validation();
                $this->CommentsModel->comment_id = $comment_id;
                $this->CommentsModel->assign($_POST);
                $validate->check('$_POST', ['comment'=>['display'=>'Comment', 'required'=>true]], true);
                if($validate->passed()) {
                    if($this->CommentsModel->updateComment()) {
                        $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$result->post_id]]);
                        Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$res->post_id);
                    }
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('editors/edit_comment');
        }

        public function deleteAction(int $post_id, int $comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->CommentsModel->post_id = $post_id;
            $this->CommentsModel->comment_id = $comment_id;
            if($this->CommentsModel->deleteComment()) {
                $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
                if(Session::get(USER_SESSION_NAME) != $res->user_id) {
                    $this->NotificationsModel->removeCommentNotification($res->user_id, $comment_id);
                }
                Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$res->post_id);
            }
        }

        public function promoteAction(int $post_id, int $comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->CommentPromotesModel->post_id = $post_id;
            $this->CommentPromotesModel->comment_id = $comment_id;
            if($this->CommentPromotesModel->promoteComment()) {
                $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
                Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$res->post_id);
            }
        }

        public function demoteAction(int $post_id, int $comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->CommentPromotesModel->post_id = $post_id;
            $this->CommentPromotesModel->comment_id = $comment_id;
            if($this->CommentPromotesModel->demoteComment()) {
                $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
                Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$res->post_id);
            }
        }

    }

?>