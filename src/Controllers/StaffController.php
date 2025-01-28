<?php

namespace Src\Controllers;

use Src\Database;

class StaffController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        parent::__construct();
    }

    public function index()
    {
        $pageTitle = 'Staffs';

        $users = $this->db()->paginate('users', ['first_name', 'id', 'last_name', 'email', 'phone', 'gender', 'address'], 15, 10);

        $this->render('index', compact('pageTitle', 'users'));
    }
}
