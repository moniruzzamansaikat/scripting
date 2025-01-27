<?php

require __DIR__ . '/vendor/autoload.php'; // Ensure the path to Composer's autoloader is correct

use Src\Router;

$startTime = microtime(true);

$router = new Router();

require __DIR__ . '/routes.php';
$config = require __DIR__ . '/config/app.php';
$baseUrl = $config['base_url'];

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $uri);

$endTime = microtime(true);

$requestTime = ($endTime - $startTime) * 1000;

$awesome = str_replace($baseUrl, '', $uri);

file_put_contents(
    __DIR__ . '/logs/request_time.log',
    date('Y-m-d H:i:s') . " - Request to {$awesome} took " . round($requestTime, 2) . " ms\n",
    FILE_APPEND
);
