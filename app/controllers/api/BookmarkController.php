<?php 
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;

    class BookmarkController extends Controller {
        
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Users');
            $this->loadModel('Posts');
            $this->loadModel('Bookmarks');
            
            $this->APIheaders();
        }

        public function addAction($post_id) {
            if(Session::exists(USER_SESSION_NAME) && is_numeric($post_id)) {
                $result = $this->PostsModel->findFirst(["conditions"=>'post_id = ?', "bind"=>[$post_id]]);
                if($this->BookmarksModel->insertBookmark(intval($post_id), $result->user_id)) {
                    $bookmark_count = $this->BookmarksModel->getBookmarkCount(Session::get(USER_SESSION_NAME));
                   echo json_encode(['status' => true, 'bookmarkCount' => $bookmark_count]);
                }
            } else {
                die();
            }
        }
    }
?>