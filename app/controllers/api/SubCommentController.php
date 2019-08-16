<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Validation;
    use Core\CSRF;

    class SubCommentController extends Controller {
        private $post_id;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Posts');
            $this->loadModel('Comments');
            $this->loadModel('SubComments');
            $this->loadModel('Notifications');

            $this->APIheaders();
        }

        //@params $user_id: user ID of the main comment 
        public function addAction(int $post_id, int $comment_id, int $user_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            
            if($_POST) {
                $validate = new Validation();
                $this->SubCommentsModel->post_id = $post_id;
                $this->SubCommentsModel->comment_id = $comment_id;
                $this->SubCommentsModel->assign($_POST);
                $validate->check('$_POST', ['sub_comment'=>['display'=>'Comment', 'required'=>true]], false);
                if($validate->passed()) {
                    if($this->SubCommentsModel->insertSubComment($user_id)) {
                        echo json_encode(['status' => true]);
                        return;
                    }
                }
                echo json_encode(['status' => false]);
                return;
            }
        }
        
        public function editAction(int $sub_comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $sub_comment = "";

            if($sub_comment_id != 0) {
                $result = $this->SubCommentsModel->findFirst([
                                'conditions'=>'sub_comment_id = ? AND user_id = ?', 
                                'bind'=>[$sub_comment_id, Session::get(USER_SESSION_NAME)]
                            ]);
                $sub_comment = $result->sub_comment;
            }
            
            if($_POST) {
                $validate = new Validation();
                $this->SubCommentsModel->sub_comment_id = $sub_comment_id;
                $this->SubCommentsModel->assign($_POST);
                $validate->check('$_POST', ['sub_comment'=>['display'=>'Comment', 'required'=>true]], false);
                if($validate->passed()) {
                    $this->SubCommentsModel->updateSubComment();
                    echo json_encode(['status' => true]);
                    return;
                }
                echo json_encode(['status' => false]);
                return;
            }
            echo json_encode(["subComment" => $sub_comment, "csrf_token" => CSRF::generateToken()]);
        }

        public function deleteAction(int $post_id, int $sub_comment_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->SubCommentsModel->sub_comment_id = $sub_comment_id;
            if($this->SubCommentsModel->deleteSubComment()) {
                $resSC = $this->SubCommentsModel->findFirst(['conditions'=>'sub_comment_id = ?', 'bind'=>[$sub_comment_id]]);
                $resC = $this->CommentsModel->findFirst(['conditions'=>'comment_id = ?', 'bind'=>[$resSC->comment_id]]);
                if(Session::get(USER_SESSION_NAME) != $resC->user_id) {
                    $this->NotificationsModel->removeSubCommentNotification($resC->user_id, $sub_comment_id); 
                }
                echo json_encode(['status' => true]);
            }
        }

    }

?>