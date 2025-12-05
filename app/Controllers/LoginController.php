<?php

namespace App\Controllers;

use App\Core\App;
use Exception;

class LoginController
{

    public function index()
    {
        return view('site/login');
    }

    public function login()
    {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $senhaHash = App::get('database')->findByEmail('usuarios', $email)->SENHA;

        if (password_verify($senha, $senhaHash)) {            
            $usuario = App::get('database')->verificaLogin($email, $senhaHash);
        }

        if($usuario != false){
            session_start();
            $_SESSION['user'] = $usuario;

            if($usuario->IS_ADMIN == 1){
                $_SESSION['admin'] = true;
            }
            else {
                $_SESSION['admin'] = false;
            }

            header('Location: /admin/dashboard');

        }
        else{
            session_start();
            $_SESSION['mensagem'] = "Email ou senha incorretos.";
            header('Location: /login');
        }
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }
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