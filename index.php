<?php 
    use Core\Router;
    use Core\Session;
    use Core\Cookie;
    use App\Models\Users;
    
    define('DS', DIRECTORY_SEPARATOR); //get the directory separator of the current system
    define('ROOT', dirname(__FILE__)); //get the root file path

    //configuration and helper function files
    require_once(ROOT . DS . 'config' . DS . 'Config.php');
    require_once(ROOT . DS . 'app' . DS .  'libs' . DS . 'Helpers.php');
    
    //autoload class function
    function autoload($className) {
        $path = explode('\\', $className);
        $class = array_pop($path);
        $dir = ROOT . DS . strtolower(implode(DS, $path)) . DS . $class . '.php';
        if(file_exists($dir)) {
            require_once($dir);
        }
    }

    //autoload the autoload function
    spl_autoload_register('autoload');

    session_start();

    //get the array format of the url
    $url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : [];

    /*** Log user in using cookies*/
    if(!Session::exists(USER_SESSION_NAME) && Cookie::exists(USER_COOKIE_NAME)) Users::getCookieForLogin();


    //route request
    Router::route($url);

?>