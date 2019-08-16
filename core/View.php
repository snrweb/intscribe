<?php 
    namespace Core;

    class View {

        protected $head, $body, $outputBuffer, $siteTitle = SITE_TITLE, $layout = DEFAULT_LAYOUT; 
        public $urlParams, $product_rating, $success = '', $danger = '';

        public function __construct() {}

        public function render($viewName) {
            $viewNameArray = explode('/', $viewName);
            $viewNamePath = implode(DS, $viewNameArray);

            if(file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $viewNamePath . '.php')) {
                include(ROOT . DS . 'app' . DS . 'views' . DS . $viewNamePath . '.php');
                include(ROOT . DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $this->layout . '.php');
            } else {
                die('The view '.$viewNamePath.' does not exist');
            }

        }

        public function content($type) {
            if($type === 'head') {
                return $this->head;
            } elseif($type === 'body') {
                return $this->body;
            }
            return false;
        }

        public function getSiteTitle() {
            return $this->siteTitle;
        }

        public function setSiteTitle($title) {
            $this->siteTitle = $title;
        }

        public function setLayout($path) {
            $this->layout = $path;
        }
        
        public function start($type) {
            $this->outputBuffer = $type;
            ob_start();
        }

        public function end() {
            if($this->outputBuffer === 'head') {
                $this->head = ob_get_clean();
            } elseif($this->outputBuffer === 'body') {
                $this->body = ob_get_clean();
            } else {
                die('Run the start method first');
            }
        }

        public function component($components) {
            $componentsArray = explode('/', $components);
            $componentsPath = implode(DS, $componentsArray);
            if(file_exists(ROOT .DS. 'app' .DS. 'views' .DS. 'components' .DS. $componentsPath . '.php')) {
                include(ROOT .DS. 'app' .DS. 'views' .DS. 'components' .DS. $componentsPath . '.php');
            } else {
                include(ROOT .DS. 'app' .DS. 'views' .DS. $componentsPath . '.php');
            }
        }

        public function successMsg() {
            if ($this->success === '') {
                return '';
            }
            return '<span class="success-alert">'.$this->success.'</span>';
        }

        public function errorMsg($error = '') {
            $err = $error;
            if ($this->danger === '' && $error === '') {
                return '';
            } elseif ($this->danger != '') {
                $err = $this->danger;
            }
            return '<span class="error-alert">'.$err.'</span>';
        }

        public function setFormErrors($errors = []) {
            foreach($errors as $error) {
                $this->{$error[1].'_error'} = '<span class="inputError">'.$error[0].'</span>';
            }
        }

    }
?>