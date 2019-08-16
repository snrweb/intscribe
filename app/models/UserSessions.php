<?php 
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    use Core\Cookie;

    class UserSessions extends Model {

        public function __construct() {
            parent::__construct('user_sessions');
        }

        /*****
         * Checks if the cookie name exists on the user's device;
         * if the cookie value exists in the database.
         * 
         * Returns the result to the Users Model.
         */
        public static function getUserCookie() {
            $userSessionModel = new self();
            if(Cookie::exists(USER_COOKIE_NAME)) {
                $userSession = $userSessionModel->findFirst(['conditions'=>'user_agent = ? AND session = ?', 
                                        'bind'=>[Session::getUserAgent(), Cookie::get(USER_COOKIE_NAME)]]);
                if(!$userSession) return false;
                return $userSession;
            }
        }

    }

?>