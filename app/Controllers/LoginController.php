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

        $usuario = App::get('database')->verificaLogin($email, $senha);

        if($usuario != false){
            session_start();
            $_SESSION['id'] = $usuario->ID;
            
            // if($usuario->IS_ADMIN == 1){
            //     header('Location: /admin/dashboard');
            // } else {
            //     header('Location: /landingpage');
            // }

            header('Location: /admin/dashboard');
        }
        else{
            session_start();
            $_SESSION['mensagem'] = "Email ou senha incorretos.";
            header('Location: /login');
        }
    }
}