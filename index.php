<?php

require __DIR__ . '/vendor/autoload.php'; // Ensure the path to Composer's autoloader is correct

use Src\Router;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


// Create a Monolog instance
$log = new Logger('request_logger');

// Create a stream handler to log to a file
$log->pushHandler(new StreamHandler(__DIR__ . '/logs/request_time.log', Logger::INFO));

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

$log->info(strtoupper($method) . ": {$awesome} took " . round($requestTime, 2) . " ms");
