<?php

namespace App\Controllers;

use App\Core\App;
use Exception;

class PostController
{

    public function index()
    {
        $database = App::get('database');
        $limit = 5;
        $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $searchTerm = trim($_GET['search']) ?? null;
        $searchColumn = $searchTerm ? 'TITULO' : null;


        $total_posts = $database->countAll('posts',$searchColumn, $searchTerm);

        $total_pages = ceil($total_posts / $limit);

        if ($page > $total_pages && $total_pages > 0) {
            $page = $total_pages;
        }
        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $posts = $database->selectPaginated('posts', $limit, $offset, $searchColumn, $searchTerm);


        return view('admin/listaposts', [
            'posts' => $posts,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'search_term' => $searchTerm
        ]);
    }

    public function store()
    {
        $database = App::get('database');

        $imagePath = 'assets/images/default.png';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/assets/images/';
            $tmpName = $_FILES['image']['tmp_name'];
            $imageName = time() . '_' . basename($_FILES['image']['name']);

            $targetPath = $uploadDir . $imageName;

            if(move_uploaded_file($tmpName, $targetPath)) {
                $imagePath = 'assets/images/' . $imageName;
            }
            else {
                throw new Exception('Erro ao fazer upload da imagem.');
            }
        }

        $parameters = [
            'TITULO' => $_POST['titulo'],
            'CONTEUDO' => $_POST['conteudo'],
            'AVALIACAO' => $_POST['rating'],
            'IMAGEM' => $imagePath,
            'CATEGORIA' => $_POST['categoria'],
            'AUTOR_ID' => 1,
            'DATA_POSTAGEM' => date('Y-m-d H:i:s')
        ];

        $database->insert('Posts', $parameters);

        return redirect('admin/listaposts');
    }

    public function update()
    {
        $database = App::get('database');

        $id = $_POST['id'];

        $post = $database->findById('Posts', $id);
        $imagePath = $post->IMAGEM;

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/assets/images/';
            $tmpName = $_FILES['imagem']['tmp_name'];
            $imageName = time() . '_' . basename($_FILES['imagem']['name']);

            $targetPath = $uploadDir . $imageName;

            if(move_uploaded_file($tmpName, $targetPath)) {
                $newimagePath = 'assets/images/' . $imageName;

                if($imagePath !== 'assets/images/default.png' && file_exists('public/' . $imagePath)) {
                    @unlink('public/' . $imagePath);
                }
                $imagePath = $newimagePath;
            }
            else {
                throw new Exception('Erro ao fazer upload da imagem.');
            }
        }

        $parameters = [
            'TITULO' => $_POST['titulo'],
            'CONTEUDO' => $_POST['conteudo'],
            'AVALIACAO' => $_POST['rating'],
            'CATEGORIA' => $_POST['categoria'],
            'DATA_EDICAO' => date('Y-m-d H:i:s'),
            'IMAGEM' => $imagePath
        ];

        $database->update('Posts', $id, $parameters);

        return redirect('admin/listaposts');
    }

    public function delete()
    {
        $database = App::get('database');

        $id = $_POST['id'];

        $post = $database->findById('Posts', $id);
        if($post && $post->IMAGEM && $post->IMAGEM !== 'assets/images/default.png' && file_exists('public/' . $post->IMAGEM)) {
            @unlink('public/' . $post->IMAGEM);
        }

        $database->deleteById('Posts', $id);

        return redirect('admin/listaposts');
    }
}