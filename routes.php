<?php

use Src\Controllers\SiteController;
use Src\Controllers\StaffController;

$authMiddleware = function () {
    echo "Authenticating...<br>";
};

$router->add('GET', '/', [new SiteController(), 'home']);
$router->add('GET', '/about', [new SiteController(), 'about']);
$router->add('GET', '/contact', [new SiteController(), 'contact']);
$router->add('POST', '/contact', [new SiteController(), 'submitContact']);
$router->add('GET', '/staffs', [new StaffController(), 'index']);

$router->group('/api', [$authMiddleware], function ($router) {
    $router->add('GET', '/user/{id}', function ($params) {
        echo "User ID: " . $params['id'];
    });
});