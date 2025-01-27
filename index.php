<?php

require __DIR__ . '/vendor/autoload.php';

use Src\Router;

$router = new Router();

require __DIR__ . '/routes.php'; 

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $uri);
