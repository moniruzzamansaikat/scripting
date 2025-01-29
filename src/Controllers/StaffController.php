<?php

namespace Src\Controllers;

use Src\Database;

class StaffController extends Controller
{
    public function index()
    {
        $pageTitle = 'Staffs';

        $users = $this->db()->select('users', ['first_name', 'id', 'last_name', 'email', 'phone', 'gender', 'address'], 15, 10);

        $this->render('index', compact('pageTitle', 'users'));
    }
}
