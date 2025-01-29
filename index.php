<?php

require __DIR__ . '/vendor/autoload.php'; 

use Src\Router;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('request_logger');

$log->pushHandler(new StreamHandler(__DIR__ . '/logs/request_time.log', Logger::INFO));

$startTime = microtime(true);

$router = new Router();
$router->registerControllersAutomatically();

$config = require __DIR__ . '/config/app.php';
$baseUrl = $config['base_url'];

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $uri);

$endTime = microtime(true);

$requestTime = ($endTime - $startTime) * 1000;

$awesome = str_replace($baseUrl, '', $uri);

$log->info(strtoupper($method) . ": {$awesome} took " . round($requestTime, 2) . " ms");
