<?php

namespace Src\Controllers;

use Src\Attributes\Auth;
use Src\Attributes\Route;
use Src\Models\User;

class StaffController extends Controller
{
    #[Auth]
    #[Route('GET', '/users')]
    public function users()
    {
        $users = (new User)->limit(10)->get();

        $this->json($users);
    }
    
    #[Auth]
    #[Route('GET', '/staffs')]
    public function index()
    {
        $pageTitle = 'Users';
        $users        = $this->db()->from('users')->limit(15)->get();

        $this->render('index', compact('pageTitle', 'users'));
    }
}
