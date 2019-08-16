<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Interests;
    use Core\Router;

    class BookmarkController extends Controller {
        
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Posts');
            $this->loadModel('Bookmarks');
        }

        public function addAction($post_id, $page_name = '') {
            if(Session::exists(USER_SESSION_NAME) && is_numeric($post_id)) {
                $result = $this->PostsModel->findFirst(["conditions"=>'post_id = ?', "bind"=>[$post_id]]);
                if($this->BookmarksModel->insertBookmark(intval($post_id), $result->user_id)) {
                    if($page_name == '') {
                        Router::redirect('');
                    } elseif($page_name == 'interest') {
                        Router::redirect('interest/'.str_replace(" ", "-", $result->post_int));
                    } elseif($page_name == 'post') {
                        Router::redirect('post/'.str_replace([" ", "?"], ["-", ""], $result->post_title).'-'.$post_id);
                    } elseif(is_numeric($page_name)) {
                        $result = $this->UsersModel->findUserByID(intval($page_name))->username;
                        Router::redirect('user/'.str_replace(" ", "-", $result).'-'.$page_name);
                    }
                }
            } else {
                Router::redirect('login');
            }
        }
    }
?>