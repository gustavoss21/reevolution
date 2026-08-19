<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(0);
require dirname(__FILE__,2) . '/vendor/autoload.php';
require dirname(__FILE__, 2) .'/router.php';

use Routes\Route;

$route = new Route();
$route->createRoute($_SERVER,$_REQUEST);
$route->route();
