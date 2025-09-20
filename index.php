<?php
require __DIR__ . '/vendor/autoload.php';
require 'router.php';

use Routes\Route;

$route = new Route();
$route->setMethod($_SERVER['REQUEST_METHOD']);
$route->setAction(
    $_SERVER['REQUEST_URI'],
    $_REQUEST
)->route();
