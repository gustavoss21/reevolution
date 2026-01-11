<?php
require dirname(__FILE__,2) . '/vendor/autoload.php';
require dirname(__FILE__, 2) .'/router.php';

use Routes\Route;

$route = new Route();
$route->setMethod($_SERVER['REQUEST_METHOD']);
$route->setAction(
    $_SERVER['REQUEST_URI'],
    $_REQUEST
)
->setBody($_REQUEST)
->route();
