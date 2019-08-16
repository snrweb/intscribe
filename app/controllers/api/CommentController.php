<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Validation;
    use Core\CSRF;

    class CommentController extends Controller {
        private $post_id;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Posts');
            $this->loadModel('Comments');
            $this->loadModel('CommentPromotes');
            $this->loadModel('Notifications');

            $this->APIheaders();
        }

        //@params $user_id: user ID of post that is commented on
        public function addAction(int $post_id, int $user_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            
            if($_POST) {
                $validate = new Validation();
                $this->CommentsModel->post_id = $post_id;
                $this->CommentsModel->assign($_POST);
                $validate->check('$_POST', ['comment'=>['display'=>'Comment', 'required'=>true]], false);
                if($validate->passed()) {
                    if($this->CommentsModel->insertComment($user_id)) {
                        echo json_encode(['status' => true]);
                        return;
                    }
                } 
                echo json_encode(['status' => false]);
                return;
            }
        }
        
        public function editAction(int $comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $comment = "";

            if($comment_id != 0) {
                $result = $this->CommentsModel->findFirst([
                                    'conditions'=>'comment_id = ? AND user_id = ?', 
                                    'bind'=>[$comment_id, Session::get(USER_SESSION_NAME)]
                                ]);
                $comment = $result->comment;
            }
            
            if($_POST) {
                $validate = new Validation();
                $this->CommentsModel->comment_id = $comment_id;
                $this->CommentsModel->assign($_POST);
                $validate->check('$_POST', ['comment'=>['display'=>'Comment', 'required'=>true]], false);
                if($validate->passed()) {
                    if($this->CommentsModel->updateComment()) {
                        echo json_encode(['status' => true]);
                        return;
                    }
                } 
                echo json_encode(['status' => false]);
                return;
            }
            echo json_encode(["comment" => $comment, "csrf_token" => CSRF::generateToken()]);
        }

        public function deleteAction(int $post_id, int $comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->CommentsModel->post_id = $post_id;
            $this->CommentsModel->comment_id = $comment_id;
            if($this->CommentsModel->deleteComment()) {
                $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
                if(Session::get(USER_SESSION_NAME) != $res->user_id) {
                    $this->NotificationsModel->removeCommentNotification($res->user_id, $comment_id);
                }
                echo json_encode(['status' => true]);
            }
        }

        public function promoteAction(int $post_id, int $comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->CommentPromotesModel->post_id = $post_id;
            $this->CommentPromotesModel->comment_id = $comment_id;
            if($this->CommentPromotesModel->promoteComment()) {
                $sum = $this->CommentPromotesModel->getSumOfPromotes($post_id);
                echo json_encode(['status' => true, "sum" => $sum]);
            }
        }

        public function demoteAction(int $post_id, int $comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->CommentPromotesModel->post_id = $post_id;
            $this->CommentPromotesModel->comment_id = $comment_id;
            if($this->CommentPromotesModel->demoteComment()) {
                $sum = $this->CommentPromotesModel->getSumOfPromotes($post_id);
                echo json_encode(['status' => true, "sum" => $sum]);
            }
        }

    }

?>