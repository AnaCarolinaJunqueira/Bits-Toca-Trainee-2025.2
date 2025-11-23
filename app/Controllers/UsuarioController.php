<?php

namespace App\Controllers;

use App\Core\App;
use Exception;

class UsuarioController
{

    public function index()
    {
        $database = App::get('database');
        $limit = 5;
        $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $searchTerm = trim($_GET['search']) ?? null;
        $searchColumn = $searchTerm ? 'NOME' : null;


        $total_usuarios = $database->countAll('usuarios',$searchColumn, $searchTerm);

        $total_pages = ceil($total_usuarios / $limit);

        if ($page > $total_pages && $total_pages > 0) {
            $page = $total_pages;
        }
        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $usuarios = $database->selectPaginated('usuarios', $limit, $offset, $searchColumn, $searchTerm);


        return view('admin/listausuarios', [
            'usuarios' => $usuarios,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'search_term' => $searchTerm
        ]);
    }

    public function store()
    {
        $database = App::get('database');

        $imagePath = 'assets/avatars/default.png';

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/assets/avatars/';
            $tmpName = $_FILES['imagem']['tmp_name'];
            $imageName = time() . '_' . basename($_FILES['imagem']['name']);

            $targetPath = $uploadDir . $imageName;

            if(move_uploaded_file($tmpName, $targetPath)) {
                $imagePath = 'assets/avatars/' . $imageName;
            }
            else {
                throw new Exception('Erro ao fazer upload da imagem.');
            }
        }

        $parameters = [
            'NOME' => $_POST['nome'],
            'EMAIL' => $_POST['email'],
            'AVALIACAO' => $_POST['rating'],
            'AVATAR' => $imagePath,
            'IS_ADMIN' => isset($_POST['is_admin']) ? 1 : 0,
            'SENHA' => password_hash($_POST['senha'], PASSWORD_DEFAULT),
        ];

        $database->insert('usuarios', $parameters);

        return redirect('admin/listausuarios');
    }

    public function update()
    {
        $database = App::get('database');

        $id = $_POST['id'];

        $usuarios = $database->findById('usuarios', $id);
        $imagePath = $usuarios->AVATAR;

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/assets/avatars/';
            $tmpName = $_FILES['imagem']['tmp_name'];
            $imageName = time() . '_' . basename($_FILES['imagem']['name']);

            $targetPath = $uploadDir . $imageName;

            if(move_uploaded_file($tmpName, $targetPath)) {
                $newimagePath = 'assets/avatars/' . $imageName;

                if($imagePath !== 'assets/avatars/default.png' && file_exists('public/' . $imagePath)) {
                    @unlink('public/' . $imagePath);
                }
                $imagePath = $newimagePath;
            }
            else {
                throw new Exception('Erro ao fazer upload da imagem.');
            }
        }

        $parameters = [
            'NOME' => $_POST['nome'],
            'EMAIL' => $_POST['email'],
            'AVALIACAO' => $_POST['rating'],
            'AVATAR' => $imagePath,
            'IS_ADMIN' => isset($_POST['is_admin']) ? 1 : 0,
            'SENHA' => password_hash($_POST['senha'], PASSWORD_DEFAULT),
        ];

        $database->update('usuarios', $id, $parameters);

        return redirect('admin/listausuarios');
    }

    public function delete()
    {
        $database = App::get('database');

        $id = $_POST['id'];

        $usuarios = $database->findById('usuarios', $id);
        if($usuarios && $usuarios->AVATAR && $usuarios->AVATAR !== 'assets/avatars/default.png' && file_exists('public/' . $usuarios->AVATAR)) {
            @unlink('public/' . $usuarios->AVATAR);
        }

        $database->deleteById('usuarios', $id);

        return redirect('admin/listausuarios');
    }
}