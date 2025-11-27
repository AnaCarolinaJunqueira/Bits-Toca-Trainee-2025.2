<?php

namespace App\Controllers;

use App\Core\App;
use Exception;

class DashboardController
{

    public function index()
    {
        return view('admin/dashboard');
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /login');
    }
}