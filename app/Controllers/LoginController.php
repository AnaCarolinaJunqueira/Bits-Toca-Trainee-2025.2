<?php

namespace App\Controllers;

use App\Core\App;
use Exception;

class LoginController
{

    public function index()
    {
        return view('site/loginpage');
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
            $_SESSION['id'] = $usuario->ID;

            header('Location: /dashboard');
        }
        else{
            session_start();
            $_SESSION['mensagem'] = "Email ou senha incorretos.";
            header('Location: /login');
        }
    }
}