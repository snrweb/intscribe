<?php
    namespace Core;
    
    class Sanitise {

        public static function input($dirty) {
            return htmlentities($dirty, ENT_QUOTES, "UTF-8");
        }

        public static function get($input) {
            if(isset($_POST[$input])) {
                return self::input($_POST[$input]);
            } elseif(isset($_GET[$input])) {
                return self::input($_GET[$input]);
            } elseif(isset($_FILES[$input]['name'])) {
                return self::input($_FILES[$input]['name']);
            }
        }
    }

?>