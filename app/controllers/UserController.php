<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Validation;
    use Core\Session;
    use Core\Router;

    class UserController extends Controller {
        private $user_id, $user;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Posts');
            $this->loadModel('Bookmarks');
            $this->loadModel('Follow');
            $this->loadModel('Interests');

            $user_id = explode("-", $action);
            $this->user_id = $user_id = str_replace("Action", "", $user_id[count($user_id)-1]);
            if(is_numeric($user_id)){
                $this->setUserDetailsCount(intval($user_id));
            }
            
            $this->view->page_name = $user_id;
        }

        private function setUserDetailsCount(int $user_id) {
            $this->view->user = $user = $this->UsersModel->findFirst(["conditions"=>'user_id = ?', "bind"=>[$user_id]]);
            
            $this->view->bookmark_count = $this->BookmarksModel->getBookmarkCount($user_id);
            $this->view->post_count = $this->PostsModel->getPostCreatedByUserCount($user_id);
            $this->view->follower_count = $this->FollowModel->getFollowersCount($user_id);
            $this->view->following_count = $this->FollowModel->getFollowingCount($user_id);
            $this->view->interest_count = $this->InterestsModel->fetchInterestCount($user_id);

            if($user_id !== Session::get(USER_SESSION_NAME)) {
                $this->view->isFollowing = $this->checkIsCurrentUserFollowsViewedUser($user_id);
            }
        }

        public function checkIsCurrentUserFollowsViewedUser($user_id) {
            $result = $this->FollowModel->findFirst(['conditions'=>'follower = ? AND following = ?', 
                                        'bind'=>[Session::get(USER_SESSION_NAME), $user_id]]);
            if(empty($result)) {
                return false;
            }
            return true;
        }

        public function paramsAction($cmpType = "") { 
            switch($cmpType) {
                case "" :
                    $this->view->cmp = '';
                    $this->view->posts = $this->PostsModel->fetchAllPostCreatedByUser($this->user_id);
                    break;
                case "interest" :
                    $this->view->cmp = 'interest';
                    if($this->user_id != Session::get(USER_SESSION_NAME)) Router::redirect('');
                    $this->view->interests = $this->InterestsModel->fetchInterests($this->user_id);
                    break;
                case "bookmark" :
                    $this->view->cmp = 'bookmark';
                    if($this->user_id != Session::get(USER_SESSION_NAME)) Router::redirect('');
                    $this->view->posts = $this->BookmarksModel->fetchBookmarkedPosts($this->user_id);
                    break;
                case "following" :
                    $this->view->cmp = 'following';
                    $this->view->following = $this->FollowModel->fetchFollowing($this->user_id);
                    break;
                case "follower" :
                    $this->view->cmp = 'follower';
                    $this->view->followers = $this->FollowModel->fetchFollowers($this->user_id);
                    $this->view->followings = $this->FollowModel->fetchLiteFollowing();
                    break;
            }
            $this->view->render('user/user');
        }

        public function editProfileAction() {
            $this->view->username_error = $this->view->csrf_token_error = $this->view->username = '';
            $this->view->profile_image_error = '';
            $this->view->user = $res =  $this->UsersModel->findFirst(["conditions"=>'user_id = ?', 
                                                                        "bind"=>[Session::get(USER_SESSION_NAME)]]);

            if($_POST) {
                $this->UsersModel->assign($_POST);
                $this->assignToView($_POST);

                //instantiate the Validation class
                $validate = new Validation();
                $validate->check('$_POST', $this->UsersModel->validateEdit(), true);
                
                if($validate->passed()) {
                    if($this->UsersModel->editProfile($res->profile_image)) {
                        Router::redirect('user/'.str_replace(" ", "-", $res->username).'-'.$res->user_id);
                    }
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('user/editProfile');
        }

    }