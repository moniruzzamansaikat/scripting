<?php

use Src\Controllers\StaffController;

$router->add('GET', '/', function () {
    echo "Welcome to the Home Page!";
});

$router->add('GET', '/about', function () {
    echo "This is the About Page!";
});

$router->add('GET', '/contact', function () {
    echo "Contact us here!";
});

$router->add('GET', '/staffs', [new StaffController(), 'index']);

