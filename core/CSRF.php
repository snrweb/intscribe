<?php
    namespace Core;

    class CSRF {

        public static function generateToken() {
            $token = base64_encode(openssl_random_pseudo_bytes(32));
            Session::set('csrf_token', $token);
            return $token;
        }

        public static function checkToken($token) {
            return (Session::exists('csrf_token') && Session::get('csrf_token') === $token);
        }

        public static function input($error) {
            return '<input type="hidden" value="'.self::generateToken().'" name="csrf_token"/>
                    <small class="errorDisplay" style="text-align: center;">'.$error.'</small>';
        }
    }

?>