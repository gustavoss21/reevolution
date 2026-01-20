<?php
require dirname(__FILE__,2) . '/vendor/autoload.php';
require dirname(__FILE__, 2) .'/router.php';

use Routes\Route;

$route = new Route();
$route->createRoute($_SERVER,$_REQUEST);
$route->route();
