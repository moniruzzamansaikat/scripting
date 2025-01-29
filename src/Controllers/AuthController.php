<?php

namespace Src\Controllers;

use Src\Attributes\Auth;
use Src\Attributes\Guest;
use Src\Attributes\Route;
use Src\Cache;
use Src\Models\User;
use Src\Session;

class AuthController extends Controller
{

    #[Auth]
    #[Route('GET', '/logout')]
    public function logout()
    {
        if (isset($_SESSION['user_id'])) {
            Cache::delete("user_{$_SESSION['user_id']}");
        }

        session_unset();
        session_destroy();
        Session::flash('success', 'You are logged out');
        redirect('/login');
    }


    #[Guest]
    #[Route('GET', '/login')]
    public function login()
    {
        $pageTitle = 'Login';

        $this->render('auth/login', compact('pageTitle'));
    }

    #[Guest]
    #[Route('POST', '/login')]
    public function postLogin()
    {
        $email = $this->get('email');
        $user = $this->db()->from('users')->where('email', '=', $email)->first();

        if (!$user) {
            Session::flash('error', 'Invalid credentials');
            redirect('/login');
        } else {
            $_SESSION['user_id'] = $user->id;

            Cache::set("user_{$user->id}", $user);

            Session::flash('success', 'You are now logged in');
            redirect('/');
        }
    }
}
