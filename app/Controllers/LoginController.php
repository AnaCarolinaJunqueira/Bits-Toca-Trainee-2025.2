<?php

namespace App\Controllers;

use App\Core\App;

class LoginController
{
    public function index()
    {
        if (isset($_SESSION['user'])) {
            return redirect('admin/dashboard');
        }

        if (!isset($_SESSION['redirect_url']) && isset($_SERVER['HTTP_REFERER'])) {
            $path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
            
            $cleanPath = ltrim($path, '/');

            if ($cleanPath && $cleanPath !== 'login' && strpos($cleanPath, 'admin') === false) {
                $_SESSION['redirect_url'] = $cleanPath;
            }
        }

        return view('site/login');
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $database = App::get('database');
        $user = $database->findByEmail('Usuarios', $email);

        if ($user && password_verify($password, $user->SENHA)) {
            $_SESSION['user'] = $user;
            $_SESSION['id'] = $user->ID;
            
            if (isset($_SESSION['redirect_url'])) {
                $redirect = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']);
                return redirect($redirect);
            }

            return redirect('admin/dashboard');
        }

        return view('site/login', ['error' => 'Email ou senha incorretos.']);
    }

    public function logout()
    {
        session_destroy();
        return redirect('');
    }
    
    public function dashboard()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }
        return view('admin/dashboard');
    }
}