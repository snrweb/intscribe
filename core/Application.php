<?php 
    namespace Core;

    class Application {

        public function __construct() {
            $this->setErrorReport();
            $this->unregisterGlobals();
        }

        //controls error reporting for either development or production stage
        private function setErrorReport() {
            if(DEBUG) {
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
            } else {
                error_reporting(0);
                ini_set('display_errors', 0);
                ini_set('log_errors', 1);
                ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'errors.log');
            }
        }

        //unset all registered globals
        private function unregisterGlobals() {
            if (ini_get('register_globals')) {
                $globalArrays = ['_SESSION', '_COOKIE', '_POST', '_GET', '_REQUEST', '_SERVER', '_ENV', '_FILES'];
                foreach($globalArrays as $g) {
                    foreach($GLOBALS[$g] as $key=>$value) {
                        if($GLOBALS[$key] == $value) {
                            unset($GLOBALS[$key]);
                        }
                    }
                }
            }
        }

    }
?>