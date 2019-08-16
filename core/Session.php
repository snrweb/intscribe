<?php 
    namespace Core;

    class Session {

        public static function exists($name) {
            return (isset($_SESSION[$name])) ? true : false;
        }

        public static function get($name) {
            if (self::exists($name)) {
                return $_SESSION[$name];
            }
        }

        public static function set($name, $value) {
            return $_SESSION[$name] = $value;
        }

        public static function delete($name) {
            if (self::exists($name)) {
                unset($_SESSION[$name]);
            }
        }

        public static function getUserAgent() {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            return $userAgent = preg_replace('/\/[a-zA-Z0-9.]+/', '', $userAgent);
        }
    }
?>