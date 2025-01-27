<?php

use Src\Controllers\SiteController;
use Src\Controllers\StaffController;

$router->add('GET', '/', [new SiteController(), 'home']);
$router->add('GET', '/about', [new SiteController(), 'about']);
$router->add('GET', '/contact', [new SiteController(), 'contact']);
$router->add('POST', '/contact', [new SiteController(), 'submitContact']);
$router->add('GET', '/staffs', [new StaffController(), 'index']);
