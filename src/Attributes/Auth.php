<?php

namespace Src\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Auth
{
    public function __construct() {
    }

    public function handle()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
            exit();
        }
    }
}
