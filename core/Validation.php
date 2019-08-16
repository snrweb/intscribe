<?php
    namespace Core;
    use Core\Interests;

    class Validation {
        private $db = null, $passed = false, $errors = [];

        public function __construct() {
            $this->db = DB::getInstance();
        }

        public function check($source, $inputs = [], $csrf_check = false) {
            $this->errors = [];

            if($csrf_check) {
                $csrfPass = CSRF::checkToken($_POST['csrf_token']);
                if($source == '$_POST') {
                    if(!isset($_POST['csrf_token']) || !$csrfPass) {
                        $this->addErrors(["Something has gone wrong", 'csrf_token']);
                    }
                } elseif($source == '$_GET') {
                    if(!isset($_GET['csrf_token']) && !CSRF::checkToken($_GET['csrf_token'])) {
                        $this->addErrors(["Something has gone wrong", 'csrf_token']);
                    }
                }
            }

            foreach($inputs as $input => $rules) {
                $input = Sanitise::input($input);
                $display = $rules['display'];

                foreach($rules as $rule => $ruleValue) {
                    $value = Sanitise::get(trim($input));

                    if($rule === 'required' && empty($value)) {
                        $this->addErrors(["{$display} must not be empty", $input]);
                    } elseif(!empty($value)) {

                        switch($rule) {
                            case 'isUsername':
                                if(!preg_match('/^[a-zA-Z ]+$/', $value)) {
                                    $this->addErrors(["{$display} should only contain A-Z, 0-9 and underscore(_)", $input]);
                                }
                            case 'isLetters':
                                if(!preg_match('/^[a-zA-Z ]+$/', $value)) {
                                    $this->addErrors(["{$display} should only contain letters", $input]);
                                }
                            case 'min':
                                if(strlen($value) < $ruleValue) {
                                    $this->addErrors(["{$display} must be a minimum of {$ruleValue} characters", $input]);
                                } 
                            break;

                            case 'max':
                                if(strlen($value) > $ruleValue) {
                                    $this->addErrors(["{$display} must not exceed {$ruleValue} characters", $input]);
                                } 
                            break;

                            case 'isEmail':
                                if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                    $this->addErrors(["{$display} must be a valid email address", $input]);
                                } 
                            break;

                            case 'isUniqueEmail':
                                if($this->db->findFirst('users', ['conditions' => ['email = ?'], 'bind'=>[$value]])) {
                                    $this->addErrors(["{$value} already exist in the database", $input]);
                                } 
                            break;
                            
                            case 'isNumber':
                                if(!is_numeric($value)) {
                                    $this->addErrors(["{$display} must be a set of numbers", $input]);
                                } 
                            break;
                            
                            case 'isInterest':
                                if(!in_array($value, Interests::getInterests())) {
                                    $this->addErrors(["Select an {$display}", $input]);
                                } 
                            break;

                            case 'url':
                                if(!filter_var($value, FILTER_VALIDATE_URL)) {
                                    $this->addErrors(["{$display} must be a valid url", $input]);
                                }
                            break;

                            case 'isImage':
                                if(!$ruleValue) return;
                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                $mtype = finfo_file($finfo, $_FILES[$input]['tmp_name']);
                                finfo_close($finfo);
                                if(($mtype !='image/jpg')&&($mtype !='image/jpeg')&&($mtype !='image/JPG')
                                    &&($mtype !='image/JPEG')&&($mtype !='image/png')&&($mtype !='image/PNG')) {
                                    $this->addErrors(["This file is not an image", $input]);
                                }

                            case 'size':
                                if(($_FILES[$input]['size'] > ($ruleValue * 1000000))) {
                                    $this->addErrors(["Image should not be more than {$ruleValue}mb", $input]);
                                }
                        }
                    }
                }
            }
        }

        private function addErrors($error = []) {
            $this->errors[] = $error;
            if(empty($this->errors)) {
                $this->passed = true;
            } else {
                $this->passed = false;
            }
        }

        public function passed() {
            if(empty($this->errors)) {
                return $this->passed = true;
            } else {
                return $this->passed = false;
            }
        }

        public function getErrors() {
            return $this->errors;
        }
    }

?>