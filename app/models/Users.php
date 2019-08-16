<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    use Core\Cookie;
    use Core\Resize;
    use App\Models\UserSessions;

    class Users extends Model {
        public $user_id, $username, $email, $password, $confirm_password, $pwd_retrieve, $profile_image, $csrf_token;
        private $is_image_file = true;
       
        public function __construct($user_id = '') {
            parent::__construct('users');
            
            if(is_numeric($user_id) && !empty($user_id)) {
                $b = $this->db->findFirst('users', ['conditions'=>'user_id = ?', 'bind'=>[$user_id]]);
                if($b) {
                    foreach($b as $key => $value) {
                        $this->$key = $value;
                    }
                }
            }
        }

        public function validateRegistration() {
            return [
                'email' => ['display' => 'Email', 'required' => true, 'isEmail' => true, 'isUniqueEmail' => true],
                'username' => ['display' => 'Full name', 'required' => true, 'isLetters' => true, 'max' => 30],
                'password' => ['display' => 'Password', 'min' => 8, 'required' => true]
            ];
        }

        /**Registers new user */
        public function register() {
            return $this->insert([
                'email' => $this->email,
                'username' => $this->username,
                'password' => $this->password,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
        }

        public function validateEdit() {
            if(empty($_FILES["profile_image"]['name'])) $this->is_image_file = false;

            return [
                'username' => ['display' => 'Full name', 'required' => true, 'isLetters' => true, 'max' => 30],
                'profile_image' => ['display' => 'Image', 'isImage' =>  $this->is_image_file, 'size' => 4.5],
            ];
        }

        /**Edit profile */
        public function editProfile($old_image) {
            if(!$this->is_image_file) {
                return $this->update('user_id = ?', [Session::get(USER_SESSION_NAME)], ['username' => $this->username]);
            } else {
                $this->update('user_id = ?', [Session::get(USER_SESSION_NAME)], ['username' => $this->username]);
                return $this->uploadProfileImage($old_image);
            }
        }

        private function uploadProfileImage($old_image) {
            $resize = new Resize();
            
            $image = $_FILES["profile_image"]["name"];
            $ext = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
            $image = time().random_int(100, 1000000000).".".$ext;

            $update = $this->update('user_id = ?', [Session::get(USER_SESSION_NAME)], ['profile_image' => $image]);

            if($update) {
                //The old logo is deleted if it exists
                if(!empty($old_image)) unlink(ROOT . DS . 'public' . DS . 'images' . DS . 'profile_pic' . DS . $old_image);

                $resize::changeSize(//temporary image image location
                    $_FILES["profile_image"]["tmp_name"], 
                    //location to upload resized image
                    ROOT . DS . 'public' . DS . 'images' . DS . 'profile_pic' . DS . $image,
                    //Maximum width of the new resized image
                    400, 
                    //Maximum height of the new resized image
                    300,
                    //File extension of the new resized image
                    $ext,
                    //Quality of the image
                    85 );
                return true;
            }
            return false;
        }

        /**
         * Login user method.
         * 
         * if rememberMe is false, the user cookie is not stored, 
         * Cookie detail is not saved in the database for cookie login
         */
        public function login($rememberMe = false) {
            Session::set(USER_SESSION_NAME, $this->user_id);
            if($rememberMe) {
                $hash = md5(uniqid() + random_int(100, 100000));
                $userAgent = Session::getUserAgent();
                Cookie::set(USER_COOKIE_NAME, $hash, USER_COOKIE_EXPIRY);
                $fields = ['session'=>$hash, 'user_agent'=>$userAgent, 'user_id'=>$this->user_id];
                $this->db->query("DELETE FROM user_sessions WHERE user_agent = ? AND user_id = ? ", 
                                [$userAgent, $this->user_id]);
                $this->db->insert('user_sessions', $fields);
            }
        }

        /***
         * Checks if the cookie exists,
         * Login the user owner if true
         */
        public static function getCookieForLogin() {
            $userSession = UserSessions::getUserCookie();
            if($userSession) {
                $user = new self((int)$userSession->user_id);
                if($user) {
                    $user->login();
                }
            }
        }

        /**Logout any user and delete any stored cookie */
        public function logout() {
            $userAgent = Session::getUserAgent();
            $this->db->query("DELETE FROM user_sessions WHERE user_agent = ? AND user_id = ? ", 
                            [$userAgent, Session::get(USER_SESSION_NAME)]);
            Session::delete(USER_SESSION_NAME);
            if(Cookie::exists(USER_COOKIE_NAME)) {
                Cookie::delete(USER_COOKIE_NAME);
            }
            return true;
        }
        
        public function resetPassword() {
            return $this->update('email', $this->email, [
                'pwd_retrieve' => $this->pwd_retrieve,
                'password' => $this->password
            ]);
        }

        public function searchUser($params) {
            return $this->query("SELECT user_id, username, email, signature, deleted FROM users 
                        WHERE username LIKE '%".$params."%' LIMIT 10")->getResult();
        }

        public function findUserByID(int $user_id) {
            return $this->findFirst(['conditions'=>'user_id = ?', 'bind'=>[$user_id]]);
        }

        public function getUserDetails() {
            $sql = "SELECT username, email, profile_image, reg_time, signature FROM users 
                    WHERE user_id = ".Session::get(USER_SESSION_NAME)."";
            return $this->query($sql)->getResult();
        }

        public function findUser() {
            return $this->query("SELECT * FROM users WHERE email = ?", [$this->email])->getResult();
        }
        
    }

?>