<?php 
    namespace Core;
    use Core\Application;
    use Core\View;
    use Core\Session;

    class Controller extends Application {

        protected $action, $controller;
        public $view;

        public function __construct($controller, $action) {
            parent::__construct();

            $this->controller = $controller;
            $this->action = $action;
            $this->view = new View();
            
            Session::set(USER_SESSION_NAME, 1);

        }

        protected function loadModel($model) {
            $modelPath = 'App\Models\\'.$model;
            if(class_exists($modelPath)) {
                $this->{$model.'Model'} = new $modelPath();
            }
        }

        protected function assignToView($post) {
            foreach($post as $key => $value) {
                $this->view->$key = $value;
            }
        }

        protected function APIheaders() {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");
        }

    }

?>