<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));
define("BASEDIR", __DIR__);

error_reporting(E_ALL & ~E_NOTICE);

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';


error_reporting(E_ERROR);
ini_set('display_errors', 'on');


// Run the application!
try{
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
}catch (\Exception $e){
    echo '<h4>Message:</h4>';
    echo "<pre>";
    echo $e->getMessage().PHP_EOL;
    echo "</pre>";
    echo '<h4>StackTrace:</h4>';
    echo "<pre>";
    echo $e->getTraceAsString().PHP_EOL;
    echo "</pre>";
}
