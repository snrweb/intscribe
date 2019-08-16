<?php 
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Validation;
    use Core\CSRF;

    class PostController extends Controller {
        private $post_id, $ref;
        
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Interests');
            $this->loadModel('Posts');
            $this->loadModel('Comments');
            $this->loadModel('SubComments');
            $this->loadModel('VotersRecord');
            $this->loadModel('PostPromotes');
            $this->loadModel('Reports');
            $this->loadModel('Bookmarks');
            
            $post_id = explode("-", $action);
            $this->post_id = str_replace("Action", "", $post_id[count($post_id)-1]);

            $this->APIheaders();
        }

        public function insertArticleAction() {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->PostsModel->post_type = 'Article';

            if($_POST) {
                $this->PostsModel->assign($_POST);
                $validate = new Validation();
                $validate->check('$_POST', [ 
                                    'post_title'=>['display'=>'Title', 'required'=>true, 'min'=>20],
                                    'main_post'=>['display'=>'Article', 'required'=>true, 'min'=>20],
                                    'post_int'=>['display'=>'interest', 'isInterest'=>$this->PostsModel->post_int]
                                ], false);
                
                if($validate->passed() && $this->PostsModel->insertPost()) {
                    echo json_encode(['status' => true]);
                    return;
                }
                echo json_encode(['status' => false]);
                return;
            }
            $user_interests = $this->InterestsModel->fetchInterests(Session::get(USER_SESSION_NAME));
            echo json_encode(['userInterests' => $user_interests, "csrf_token" => CSRF::generateToken()]);
        }

        public function editArticleAction(int $post_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->PostsModel->post_type = 'Article';
            $this->PostsModel->post_id = $post_id;
           
            if($_POST) {
                $this->PostsModel->assign($_POST);
                $validate = new Validation();
                $validate->check('$_POST', [ 
                                    'post_title'=>['display'=>'Title', 'required'=>true, 'min'=>20],
                                    'main_post'=>['display'=>'Article', 'required'=>true, 'min'=>20],
                                    'post_int'=>['display'=>'interest', 'isInterest'=>$this->PostsModel->post_int]
                                ], false);
                
                if($validate->passed() && $this->PostsModel->updatePost()) {
                    echo json_encode(['status' => true]);
                    return;
                }
                echo json_encode(['status' => false]);
                return;
            }

        }
 

        public function insertPollAction() {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->PostsModel->post_type = 'Poll';

            if($_POST) {
                $this->PostsModel->assign($_POST);
                $validate = new Validation();
                $validate->check('$_POST', [ 
                                    'post_title'=>['display'=>'Question', 'required'=>true, 'min'=>10, 'max'=>200],
                                    'option_one'=>['display'=>'Option one', 'required'=>true],
                                    'option_two'=>['display'=>'Option two', 'required'=>true],
                                    'post_int'=>['display'=>'interest', 'isInterest'=>$this->PostsModel->post_int]
                                ], false);
                
                if($validate->passed() && $this->PostsModel->insertPost()) { 
                    echo json_encode(['status' => true]); 
                    return;
                }
                echo json_encode(['status' => false]);
                return;
            }
            $user_interests = $this->InterestsModel->fetchInterests(Session::get(USER_SESSION_NAME));
            echo json_encode(['userInterests' => $user_interests, "csrf_token" => CSRF::generateToken()]);
        }

        public function insertQuestionAction() {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->PostsModel->post_type = 'Question';

            if($_POST) {
                $this->PostsModel->assign($_POST);
                $validate = new Validation();
                $validate->check('$_POST', [ 
                                    'post_title'=>['display'=>'Question', 'required'=>true, 'min'=>10, 'max'=>200],
                                    'post_int'=>['display'=>'interest', 'isInterest'=>$this->PostsModel->post_int],
                                    'question_link'=>['display'=>'Link', 'max'=>100, 'url'=>true]
                                ], false);
                
                if($validate->passed() && $this->PostsModel->insertPost()) {
                    echo json_encode(['status' => true]); 
                    return;
                } else {
                    echo json_encode(['status' => false]); 
                    return;
                }
            }

            $user_interests = $this->InterestsModel->fetchInterests(Session::get(USER_SESSION_NAME));
            echo json_encode(['userInterests' => $user_interests, "csrf_token" => CSRF::generateToken()]);
        }

        public function paramsAction() {
            $post = $this->PostsModel->fetchMainPost($this->post_id);
            $comments = $this->CommentsModel->fetchComment($this->post_id);
            $sub_comments = $this->SubCommentsModel->getAllSubComments($this->post_id);
            echo json_encode(["post" => $post, "comments" => $comments, "subComments" => $sub_comments]);
        }

        public function postCommentsAction($post_id, $start_position) {
            $comments = $this->CommentsModel->fetchComment($post_id, $start_position);
            echo json_encode(["comments" => $comments]);
        }

        public function homePostAction($start_position = 0) {
            $posts = $this->PostsModel->fetchAllPosts($start_position);

            echo json_encode(['posts' => $posts, 'pageName' => '']);
        }

        public function otherPostAction($start_position = 0) {
            if(Session::exists(USER_SESSION_NAME)) {
                $posts = $this->PostsModel->fetchOtherPosts($start_position);
                echo json_encode(['posts' => $posts, 'pageName' => '']);
            }
        }
        

        public function interestPostAction($interestName, int $start_position) {
            $interest_name = str_replace("-", " ", $interestName);
            $posts = $this->PostsModel->fetchInterestBasedPosts($interest_name, $start_position);

            echo json_encode(['posts' => $posts, 'pageName' => 'interest']);
        }

        //Get all posts created by the userid
        public function userPostAction(string $user_id, int $start_position) {
            $user_id = explode("-", $user_id);
            $user_id = $user_id[count($user_id)-1];
            $posts = $this->PostsModel->fetchAllPostCreatedByUser($user_id, $start_position);
            echo json_encode(['posts' => $posts, 'pageName' => 'interest']);
        }

        //Get all post bookmarked by the userid
        public function bookmarkedPostAction(string $user_id, int $start_position) {
            $user_id = explode("-", $user_id);
            $user_id = $user_id[count($user_id)-1];
            $posts = $this->BookmarksModel->fetchBookmarkedPosts($user_id, $start_position);
            echo json_encode(['posts' => $posts]);
        }

        public function addVoteAction(int $post_id, int $option) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            if($this->VotersRecordModel->isClosed($post_id)) die();
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
            $result = $this->VotersRecordModel->toggleVote($post_id);
            if($result){
                $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[intval($post_id)]]);
                $sum = $res->option_one_count + $res->option_two_count + $res->option_three_count + $res->option_four_count;
                $op_one = $res->option_one_count!= 0 ? $res->option_one_count/$sum * 100: 0;
                $op_two = $res->option_two_count != 0 ? $res->option_two_count/$sum * 100: 0;
                $op_three = $res->option_three_count != 0 ? $res->option_three_count/$sum * 100 : 0;
                $op_four = $res->option_four_count != 0 ? $res->option_four_count/$sum * 100: 0;
                echo json_encode([
                    'status'=> $result,
                    'sum' => $sum,
                    'opOne'=> $op_one,
                    'opTwo'=> $op_two,
                    'opThree'=> $op_three,
                    'opFour'=> $op_four,
                    ]);
            }
        }

        public function deleteAction(int $post_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            if($this->PostsModel->softDeletePost($post_id)) {
                echo json_encode(['status' => true]);
            }
        }

        public function promoteAction(int $post_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->PostPromotesModel->post_id = $post_id;
            if($this->PostPromotesModel->promotePost()) {
                $sum = $this->PostPromotesModel->getSumOfPromotes($post_id);
                echo json_encode(['status' => true, "sum" => $sum]);
            }
        }

        public function demoteAction(int $post_id) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->PostPromotesModel->post_id = $post_id;
            if($this->PostPromotesModel->demotePost()) {
                $sum = $this->PostPromotesModel->getSumOfPromotes($post_id);
                echo json_encode(['status' => true, "sum" => $sum]);
            }
        }

        private function setRef(string $type, int $post_id, int $comment_id, int $subcomment_id) {
            if($type == 'Post') {
                $res = $this->PostsModel->findFirst(['conditions'=>'post_id = ?', 'bind'=>[$post_id]]);
                $this->ref = $res->post_title;
            } elseif ($type == 'Comment') {
                $result = $this->CommentsModel->findFirst(['conditions'=>'comment_id = ?', 'bind'=>[$comment_id]]);
                $this->ref = $result->comment;
            } elseif ($type == 'SubComment') {
                $result = $this->SubCommentsModel->findFirst(['conditions'=>'sub_comment_id = ?', 'bind'=>[$subcomment_id]]);
                $this->ref = $result->sub_comment;
            }
        }

        public function reportAction(string $type, int $post_id, int $comment_id = 0, int $subcomment_id = 0) {
            if(!Session::exists(USER_SESSION_NAME)) die();
            $this->setRef($type, $post_id, $comment_id, $subcomment_id);
            
            $report_tag = $this->ReportsModel->report_tag = $type;
            $post_id = $this->ReportsModel->post_id = $post_id;
            $comment_id = $this->ReportsModel->comment_id = $comment_id;
            $subcomment_id = $this->ReportsModel->subcomment_id = $subcomment_id;

            if($_POST) {
                $validate = new Validation();
                $validate->check('$_POST', ['report' => ['display'=>'Report', 'required'=> true, 'min'=>5]], false);
                $this->ReportsModel->assign($_POST);
                if($validate->passed()) {
                    if($this->ReportsModel->insertReport()) {
                        echo json_encode(['status' => true]);
                        return;
                    }
                } else {
                    echo json_encode(['status' => false]);
                    return;
                }
            }
            echo json_encode(['ref' => $this->ref, "csrf_token" => CSRF::generateToken()]);
        }
        
    }
?>