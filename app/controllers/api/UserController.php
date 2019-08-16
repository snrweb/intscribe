<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Validation;
    use Core\Session;
    use Core\CSRF;

    class UserController extends Controller {
        private $user_id, $user; 
        public $data = [];

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Posts');
            $this->loadModel('Bookmarks');
            $this->loadModel('Follow');
            $this->loadModel('Interests');
            
            $this->APIheaders();

            $user_id = explode("-", $action);
            $this->user_id = $user_id = str_replace("Action", "", $user_id[count($user_id)-1]);
        }

        public function setUserDetailsAction(string $user_id) {
            $user_id = explode("-", $user_id);
            $user_id = $user_id[count($user_id)-1];
            $user = $this->UsersModel->findFirst(["conditions"=>'user_id = ?', "bind"=>[$user_id]]);
            
            $bookmark_count = $this->BookmarksModel->getBookmarkCount($user_id);
            $post_count = $this->PostsModel->getPostCreatedByUserCount($user_id);
            $follower_count = $this->FollowModel->getFollowersCount($user_id);
            $following_count = $this->FollowModel->getFollowingCount($user_id);
            $interest_count = $this->InterestsModel->fetchInterestCount($user_id);

            $data = [
                "user" => $user,
                "bookmarkCount" => $bookmark_count,
                "postCount" => $post_count,
                "followerCount" => $follower_count,
                "followingCount" => $following_count,
                "interestCount" => $interest_count
            ];

            echo json_encode($data);
        }

        public function paramsAction($cmpType = "") {
            $cmp = ""; 
            $posts = $interests = $followings = $followers = $followingLite = [];
            $isFollowing = false;

            if($this->user_id !== Session::get(USER_SESSION_NAME)) {
                $isFollowing = $this->checkIsCurrentUserFollowsViewedUser($this->user_id);
            }

            switch($cmpType) {
                case "undefined" :
                    $cmp = "";
                    $posts = $this->PostsModel->fetchAllPostCreatedByUser($this->user_id);
                    break;
                case "interest" :
                    $cmp = 'interest';
                    if($this->user_id != Session::get(USER_SESSION_NAME)) die();
                    $interests = $this->InterestsModel->fetchInterests($this->user_id);
                    break;
                case "bookmark" :
                    $cmp = 'bookmark';
                    if($this->user_id != Session::get(USER_SESSION_NAME)) die();
                    $posts = $this->BookmarksModel->fetchBookmarkedPosts($this->user_id);
                    break;
                case "following" :
                    $cmp = 'following';
                    $followings = $this->FollowModel->fetchFollowing($this->user_id);
                    break;
                case "follower" :
                    $cmp = 'follower';
                    $followers = $this->FollowModel->fetchFollowers($this->user_id);
                    $followingLite = $this->FollowModel->fetchLiteFollowing();
                    break;
            }

            $data = array(
                'cmp' => $cmp, 
                'posts' => $posts, 
                'interests' => $interests, 
                'followings' => $followings, 
                'followers' => $followers, 
                'followingLite' => $followingLite,
                'isFollowing' => $isFollowing,
            );

            echo json_encode($data);
        }

        public function checkIsCurrentUserFollowsViewedUser($user_id) {
            $result = $this->FollowModel->findFirst(['conditions'=>'follower = ? AND following = ?', 
                                        'bind'=>[Session::get(USER_SESSION_NAME), $user_id]]);
            if(empty($result)) {
                return false;
            }
            return true;
        }

        public function editProfileAction() {
            $res =  $this->UsersModel->findFirst(["conditions"=>'user_id = ?', 
                                                                        "bind"=>[Session::get(USER_SESSION_NAME)]]);

            if($_POST) {
                $this->UsersModel->assign($_POST);

                $validate = new Validation();
                $validate->check('$_POST', $this->UsersModel->validateEdit(), false);
                
                if($validate->passed() && $this->UsersModel->editProfile($res->profile_image)) {
                    $user = $this->UsersModel->findFirst(["conditions"=>'user_id = ?', 
                    "bind"=>[Session::get(USER_SESSION_NAME)]]);
                    echo json_encode([
                        "status" => true,
                        "user_id" => $user->user_id, 
                        "username" => $user->username
                        ]);
                    return;
                } else {
                    echo json_encode(["status" => false]);
                    return;
                }
            }
            echo json_encode([
                        "user_id" => $res->user_id, 
                        "username" => $res->username, 
                        "profile_image" => $res->profile_image, 
                        "csrf_token" => CSRF::generateToken()
                        ]);
        }

    }