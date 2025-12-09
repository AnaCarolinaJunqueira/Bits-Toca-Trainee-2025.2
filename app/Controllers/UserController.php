<?php

namespace App\Controllers;

use App\Core\App;

class UserController {

    public function index()
    {
        $users = app::get('database')->selectAll('usuarios');
        return view('admin/listausuarios', compact('users'));
    }

    public function create()
    {
        $parameters = [
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'senha' => $_POST['senha']
        ];

        App::get('database')->insert('users',$parameters);
        header('Location: /users');
    }
}

