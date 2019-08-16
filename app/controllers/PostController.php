<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Validation;

    class PostController extends Controller {
        private $post_id;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Interests');
            $this->loadModel('Bookmarks');
            $this->loadModel('Follow');
            $this->loadModel('Posts');
            $this->loadModel('Comments');
            $this->loadModel('SubComments');
            $this->loadModel('PostPromotes');
            $this->loadModel('VotersRecord');

            $this->view->post_title_error = $this->view->main_post_error = $this->view->csrf_token_error = '';
            $this->view->post_int_error = $this->view->post_title = $this->view->main_post = '';

            $post_id = explode("-", $action);
            $this->post_id = str_replace("Action", "", $post_id[count($post_id)-1]);

            $this->view->setLayout('default');
            $this->view->page_name = 'post';
        }
 
        public function insertArticleAction() {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->PostsModel->post_type = 'Article';

            if($_POST) {
                $this->PostsModel->assign($_POST);
                $this->assignToView($_POST);
                $validate = new Validation();
                $validate->check('$_POST', [ 
                                    'post_title'=>['display'=>'Title', 'required'=>true, 'min'=>20],
                                    'main_post'=>['display'=>'Article', 'required'=>true, 'min'=>200],
                                    'post_int'=>['display'=>'interest', 'isInterest'=>$this->PostsModel->post_int]
                                ], true);
                
                if($validate->passed() && $this->PostsModel->insertPost()) {
                    Router::redirect('');
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }

            $this->view->user = $this->UsersModel->findFirst([
                "conditions"=>'user_id = ?', "bind"=>[Session::get(USER_SESSION_NAME)]
            ]);
            $this->view->user_interests = $this->InterestsModel->fetchInterests(Session::get(USER_SESSION_NAME));
            $this->view->render('editors/add_article');
        }
 
        public function editArticleAction(int $post_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->PostsModel->post_type = 'Article';
            $this->view->post_id = $this->PostsModel->post_id = $post_id;
            if(!$_POST) {
                $result = $this->PostsModel->findFirst([
                    "conditions"=>'post_id = ? AND user_id = ?', "bind"=>[$post_id, Session::get(USER_SESSION_NAME)]
                ]);
                $this->view->post_title = $result->post_title;
                $this->view->main_post = $result->main_post;
                $this->view->post_int = $result->post_int;
            }

            if($_POST) {
                $this->PostsModel->assign($_POST);
                $this->assignToView($_POST);
                $validate = new Validation();
                $validate->check('$_POST', [ 
                                    'post_title'=>['display'=>'Title', 'required'=>true, 'min'=>20],
                                    'main_post'=>['display'=>'Article', 'required'=>true, 'min'=>200],
                                    'post_int'=>['display'=>'interest', 'isInterest'=>$this->PostsModel->post_int]
                                ], true);
                
                if($validate->passed() && $this->PostsModel->updatePost()) {
                    Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $this->PostsModel->post_title).'-'.$post_id);
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }

            $this->view->user = $this->UsersModel->findFirst([
                "conditions"=>'user_id = ?', "bind"=>[Session::get(USER_SESSION_NAME)]
            ]);
            $this->view->user_interests = $this->InterestsModel->fetchInterests(Session::get(USER_SESSION_NAME));
            $this->view->render('editors/edit_article');
        }
 
        public function insertQuestionAction() {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->PostsModel->post_type = 'Question';

            if($_POST) {
                $this->PostsModel->assign($_POST);
                $this->assignToView($_POST);
                $validate = new Validation();
                $validate->check('$_POST', [ 
                                    'post_title'=>['display'=>'Question', 'required'=>true, 'min'=>50],
                                    'post_int'=>['display'=>'interest', 'isInterest'=>$this->PostsModel->post_int],
                                    'question_link'=>['display'=>'Link', 'max'=>100, 'url'=>true]
                                ], true);
                
                if($validate->passed() && $this->PostsModel->insertPost()) {
                    Router::redirect('');
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }

            $this->view->user = $this->UsersModel->findFirst([
                "conditions"=>'user_id = ?', "bind"=>[Session::get(USER_SESSION_NAME)]
            ]);
            $this->view->user_interests = $this->InterestsModel->fetchInterests(Session::get(USER_SESSION_NAME));
            $this->view->render('editors/add_question');
        }
 
        public function editQuestionAction(int $post_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->PostsModel->post_type = 'Question';
            $this->view->post_id = $this->PostsModel->post_id = $post_id;
            if(!$_POST) {
                $result = $this->PostsModel->findFirst([
                    "conditions"=>'post_id = ? AND user_id = ?', "bind"=>[$post_id, Session::get(USER_SESSION_NAME)]
                ]);
                $this->view->post_title = $result->post_title;
                $this->view->post_int = $result->post_int;
            }

            if($_POST) {
                $this->PostsModel->assign($_POST);
                $this->assignToView($_POST);
                $validate = new Validation();
                $validate->check('$_POST', [ 
                                    'post_title'=>['display'=>'Question', 'required'=>true, 'min'=>50],
                                    'post_int'=>['display'=>'interest', 'isInterest'=>$this->PostsModel->post_int]
                                ], true);
                
                if($validate->passed() && $this->PostsModel->updatePost()) {
                    Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $this->PostsModel->post_title).'-'.$post_id);
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }

            $this->view->user = $this->UsersModel->findFirst([
                "conditions"=>'user_id = ?', "bind"=>[Session::get(USER_SESSION_NAME)]
            ]);
            $this->view->user_interests = $this->InterestsModel->fetchInterests(Session::get(USER_SESSION_NAME));
            $this->view->render('editors/edit_question');
        }
 
        public function insertPollAction(int $count = 2) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->PostsModel->post_type = 'Poll';
            $this->view->count = $count;

            $this->view->option_one = $this->view->option_two = $this->view->option_three = $this->view->option_four = '';
            $this->view->option_one_error = $this->view->option_two_error = '';
            $this->view->option_three_error = $this->view->option_four_error = '';

            if($_POST) {
                $this->PostsModel->assign($_POST);
                $this->assignToView($_POST);
                $validate = new Validation();
                $validate->check('$_POST', [ 
                                    'post_title'=>['display'=>'Question', 'required'=>true, 'min'=>10, 'max'=>200],
                                    'option_one'=>['display'=>'Option one', 'required'=>true],
                                    'option_two'=>['display'=>'Option two', 'required'=>true],
                                    'post_int'=>['display'=>'interest', 'isInterest'=>$this->PostsModel->post_int]
                                ], true);
                
                if($validate->passed() && $this->PostsModel->insertPost()) Router::redirect('');
                $this->view->setFormErrors($validate->getErrors());
            }
            $this->view->user = $this->UsersModel->findFirst([
                "conditions"=>'user_id = ?', "bind"=>[Session::get(USER_SESSION_NAME)]]);
            $this->view->user_interests = $this->InterestsModel->fetchInterests(Session::get(USER_SESSION_NAME));
            $this->view->render('editors/add_poll');
        }

        public function addVoteAction(int $post_id, int $option, $page_name) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('');
            if($this->VotersRecordModel->isClosed($post_id)) Router::redirect('');
            switch($option) {
                case 1:
                    $this->VotersRecordModel->vote_option = 'option_one_count';
                    break;
                case 2:
                    $this->VotersRecordModel->vote_option = 'option_two_count';
                    break;
                case 3:
                    $this->VotersRecordModel->vote_option = 'option_three_count';
                    break;
                case 4:
                    $this->VotersRecordModel->vote_option = 'option_four_count';
                    break;
            }

            $this->VotersRecordModel->toggleVote($post_id);

            switch($page_name) { 
                case ($page_name == '') :
                    Router::redirect('');
                    break;
                case ($page_name == 'interest') :
                    $result = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[intval($post_id)]]);
                    Router::redirect('interest/'.str_replace(" ", "-", $result->post_int));
                    break;
                case (is_numeric($page_name)) :
                    $result = $this->UsersModel->findUserByID(intval($page_name))->username;
                    Router::redirect('user/'.str_replace(" ", "-", $result).'-'.$page_name);
                    break;
            }
        }

        public function paramsAction() {
            if(Session::exists(USER_SESSION_NAME)) {
                $this->view->bookmark_pg = 'home';
                usersInfo($this->view);
            }
            $this->view->post = $this->PostsModel->fetchMainPost($this->post_id);
            $this->view->comments = $this->CommentsModel->fetchComment($this->post_id);
            $this->view->sub_comments = $this->SubCommentsModel->getAllSubComments($this->post_id);
            $this->view->render('post/main_article');
        }

        public function deleteAction(int $post_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            if($this->PostsModel->softDeletePost($post_id)) {
                Router::redirect('');
            }
        }

        public function promoteAction(int $post_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->PostPromotesModel->post_id = $post_id;
            if($this->PostPromotesModel->promotePost()) {
                $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
                Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$res->post_id);
            }
        }

        public function demoteAction(int $post_id) {
            if(!Session::exists(USER_SESSION_NAME)) Router::redirect('login');
            $this->PostPromotesModel->post_id = $post_id;
            if($this->PostPromotesModel->demotePost()) {
                $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
                Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $res->post_title).'-'.$res->post_id);
            }
        }

    }

?>