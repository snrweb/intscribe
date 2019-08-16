<?php
    namespace App\Libs;
    use Resource\PHPMailer\PHPMailer;
    use Resource\PHPMailer\PHPMailerException;

    class Email {
        private $subject, $content, $recipient; 
        public $reply_email;

        public function __construct() {
        }

        public function sendEmail() {
    
            $mail = new PHPMailer;
    
            $mail->isSMTP();                                   // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                    // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                            // Enable SMTP authentication
            $mail->Username = '';          // SMTP username
            $mail->Password = ''; // SMTP password
            $mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
            $mail->SMTPOptions = array(
    
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
    
            );
    
            $mail->Port = 25;                                 // TCP port to connect to
    
            $mail->setFrom('password_reset@intscribe.com', 'Intscribe');
            $mail->addReplyTo($this->reply_email, 'Intscribe');
            $mail->addAddress($this->recipient);   // Add a recipient

            $mail->isHTML(true);  // Set email format to HTML
    
            $mail->Subject = $this->subject;
            $mail->Body    = $this->content;
    
            if(!$mail->send()) {
                return false;
            } 
            return true;
        }

        public function setEmailSubject($subject) {
            $this->subject = $subject;
        }

        public function setRecipientEmail($email) {
            $this->recipient = $email;
        }

        public function setEmailContent($content) {
            $this->content = $content;
        }

    }

?>