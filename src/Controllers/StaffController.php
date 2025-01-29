<?php

namespace Src\Controllers;

use Src\Attributes\Route;

class StaffController extends Controller
{

    #[Route('GET', '/staffs')]
    public function index()
    {
        $pageTitle    = 'Staffs';
        $currentPage  = $_GET['page'] ?? 1;
        $perPage      = 15;
        $users        = $this->db()->paginate('users', ['first_name', 'id', 'last_name', 'email', 'phone', 'gender', 'address'], $perPage, $currentPage);
        $totalRecords = $this->db()->count('users');
        $totalPages   = ceil($totalRecords / $perPage);

        $this->render('index', compact('pageTitle', 'users', 'currentPage', 'totalPages'));
    }
}
