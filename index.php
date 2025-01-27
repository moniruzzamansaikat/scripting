<?php

require __DIR__ . '/vendor/autoload.php'; // Ensure the path to Composer's autoloader is correct

use Src\Router;

$router = new Router();

// Define routes
$router->add('GET', '/', function () {
    echo "Welcome to the Home Page!";
});

$router->add('GET', '/about', function () {
    echo "This is the About Page!";
});

$router->add('GET', '/contact', function () {
    echo "Contact us here!";
});

// Get the request method (GET, POST, etc.) and URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $uri);
