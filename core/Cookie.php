<?php 
    namespace Core;

    class Cookie {

        public static function exists($name) {
            return (isset($_COOKIE[$name])) ? true : false;
        }

        public static function get($name) {
            if (self::exists($name)) {
                return $_COOKIE[$name];
            }
        }

        public static function set($name, $value, $expiry) {
            return (setcookie($name, $value, $expiry, '/')) ? true : false;
        }

        public static function delete($name) {
            if (self::exists($name)) {
                self::set($name, '', time() - (86400 * 30));
            }
        }

    }
?>