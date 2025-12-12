<?php

namespace App\Controllers;

use App\Core\App;

class UserController {

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }
        $database = App::get('database');
        $limit = 5;
        $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $searchTerm = trim(isset($_GET['search']) ? $_GET['search'] : '') ?? null;
        $searchColumn = $searchTerm ? ['NOME','EMAIL'] : null;

        $total_users = $database->countAll('usuarios',$searchColumn, $searchTerm, $_SESSION['user']->ID, $_SESSION['user']->IS_ADMIN);

        $total_pages = ceil($total_users / $limit);

        if ($page > $total_pages && $total_pages > 0) {
            $page = $total_pages;
        }
        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $users = $database->selectPaginated('usuarios', $limit, $offset, $searchColumn, $searchTerm, $_SESSION['user']->ID, $_SESSION['user']->IS_ADMIN);

        return view('admin/listausuarios', [
            'users' => $users,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'search_term' => $searchTerm
        ]); 
    }
    private function uploadImage($fileInputName) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/assets/avatars/';
            $tmpName = $_FILES[$fileInputName]['tmp_name'];
            $imageName = time() . '_' . uniqid() . '_' . basename($_FILES[$fileInputName]['name']);
            $targetPath = $uploadDir . $imageName;

            if(move_uploaded_file($tmpName, $targetPath)) {
                return 'assets/avatars/' . $imageName;
            }
        }
        return null;
    }


    public function store()
    {
        $database = App::get('database');

        $avatar = $this->uploadImage('avatar');

        if(!$avatar) $avatar = 'assets/avatars/default.png';

        $parameters = [
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'senha' => $_POST['senha'],
            'avatar' => $avatar,
        ];

        App::get('database')->insert('usuarios',$parameters);
        return redirect('admin/listausuarios');
    }

    public function delete()
    {
        $userId = $_POST['id'];
        App::get('database')->deleteById('usuarios', $userId);
        return redirect('admin/listausuarios');
    }

 public function update()
    {
        $database = App::get('database');

        $id = $_POST['id'];

        $user = $database->findById('usuarios', $id);
        $useravatar = $user->AVATAR;
        
        $avatar = $this->uploadImage('avatar');
        if($avatar) {
            if($useravatar !== 'assets/avatars/default.png' && file_exists('public/' . $useravatar)) {
                @unlink('public/' . $useravatar);
            }
            $useravatar = $avatar;
        }

        $parameters = [
            'ID' => $_POST['id'],
            'NOME' => $_POST['nome'],
            'EMAIL' => $_POST['email'],
            'SENHA' => $_POST['senha'],
            'IS_ADMIN' => isset($_POST['is_admin']) ? 1 : 0,    
            'AVATAR' => $useravatar,
            
        ];

        $database->update('usuarios', $id, $parameters);
        return redirect('admin/listausuarios');
    }


      

}