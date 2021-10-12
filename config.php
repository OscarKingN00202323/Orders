<?php
// Define constants - values you need that don't need to change (unlike variables which can change) 
// Its handy to have them all in one place, define them once in this config file and use them anywhere in the code
define('APP_URL', 'http://localhost/cc2_awd_orders');

define('DB_SERVER', 'localhost');
define('DB_DATABASE', 'order_CA1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');


set_include_path(
  get_include_path() . PATH_SEPARATOR . dirname(__FILE__)
);

spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.php';
});

require_once "lib/global.php";

if (!isset($request)) {
  $request = new HttpRequest();
}
?>
