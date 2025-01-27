<?php

namespace Src\Controllers;

use Src\Database;

class SiteController extends Controller
{
    public function home()
    {
        $pageTitle = 'Home';

        $totalUsers = $this->db()->count('users');

        $this->render('home', compact('pageTitle', 'totalUsers'));
    }

    public function about()
    {
        $pageTitle = 'About';

        $this->render('About', compact('pageTitle'));
    }

    public function contact()
    {
        $pageTitle = 'Contact';

        $this->render('contact', compact('pageTitle'));
    }

    public function submitContact()
    {
        $name    = $this->get('name');
        $email   = $this->get('email');
        $message = $this->get('message');

        $contactId = $this->db()->insert('contacts', [
            'name' => $name,
            'email' => $email,
            'message' => $message
        ]);

        redirect('/');
    }
}
