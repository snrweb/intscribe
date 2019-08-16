<?php
    namespace App\Libs;

    class EmailLayout {

        public static function passwordLayout($code, $email) {
            return '<div style="padding: 5px 1%; font-family: Roboto, Oxygen, "Open Sans", "Helvetica Neue", sans-serif;">

                        <div style="display: flex; justify-content: left; align-items: center; margin: 20px;">
                            <img src="'.PROOT.'public/images/logo/logo.jpg" 
                                 style="max-height: 50px; max-width: 100px; margin-right: 5px;">
                            <a href="'.PROOT.'" 
                               style="font-size: 20px; font-weight: 600; text-decoration: none; color: #332469;">Orjah</a>
                        </div>
                        
                        <p style="padding-bottom: 10px;">Password Reset Link</p>
                        
                        <a style="display: block; margin-bottom: 10px; text-decoration: none;" 
                           href="'.PROOT.'password/reset/'.$code.'/'.$email.'">
                            Click here to reset your account
                        </a>

                        <div style="border-top: 1px solid #E5E5E5; padding: 4px; color: #ccc; font-size: 14px;">
                            <p>This message was sent to '.str_replace('-', ' ', $email).'</p>
                        </div>
                    
                    </div>';
        }

    }


?>