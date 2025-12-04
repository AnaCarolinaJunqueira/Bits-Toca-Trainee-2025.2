<?php

namespace App\Controllers;

use App\Core\App;
use Exception;

class PostController
{

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }
        $database = App::get('database');
        $limit = 5;
        $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $searchTerm = trim(isset($_GET['search']) ? $_GET['search'] : '') ?? null;
        $searchColumn = $searchTerm ? 'TITULO' : null;


        $total_posts = $database->countAll('posts',$searchColumn, $searchTerm, $_SESSION['id'], $_SESSION['user']->IS_ADMIN);

        $total_pages = ceil($total_posts / $limit);

        if ($page > $total_pages && $total_pages > 0) {
            $page = $total_pages;
        }
        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $posts = $database->selectPaginated('posts', $limit, $offset, $searchColumn, $searchTerm, $_SESSION['id'], $_SESSION['user']->IS_ADMIN);

        return view('admin/listaposts', [
            'posts' => $posts,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'search_term' => $searchTerm
        ]);
    }

    private function uploadImage($fileInputName) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/assets/images/';
            $tmpName = $_FILES[$fileInputName]['tmp_name'];
            $imageName = time() . '_' . uniqid() . '_' . basename($_FILES[$fileInputName]['name']);
            $targetPath = $uploadDir . $imageName;

            if(move_uploaded_file($tmpName, $targetPath)) {
                return 'assets/images/' . $imageName;
            }
        }
        return null;
    }

    public function store()
    {
        $database = App::get('database');

        $imageFeaturedPath = $this->uploadImage('imagem_featured');
        $imageRecentPath = $this->uploadImage('imagem_recent');

        if(!$imageFeaturedPath) $imageFeaturedPath = 'assets/images/default.png';
        if(!$imageRecentPath) $imageRecentPath = 'assets/images/default_recents.png';

        $parameters = [
            'TITULO' => $_POST['titulo'],
            'CONTEUDO' => $_POST['conteudo'],
            'AVALIACAO' => $_POST['rating'],
            'IMAGEM' => $imageFeaturedPath,
            'IMAGEM_RECENT' => $imageRecentPath,
            'CATEGORIA' => $_POST['categoria'],
            'AUTOR_ID' => $_SESSION['id'],
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
        $imageFeaturedPath = $post->IMAGEM;
        
        $newFeaturedPath = $this->uploadImage('imagem_featured');
        if($newFeaturedPath) {
            if($imageFeaturedPath !== 'assets/images/default.png' && file_exists('public/' . $imageFeaturedPath)) {
                @unlink('public/' . $imageFeaturedPath);
            }
            $imageFeaturedPath = $newFeaturedPath;
        }

        $imageRecentPath = $post->IMAGEM_RECENT ?? 'assets/images/default_recents.png';
        $newRecentPath = $this->uploadImage('imagem_recent');
        if($newRecentPath) {
            if($imageRecentPath !== 'assets/images/default_recents.png' && file_exists('public/' . $imageRecentPath)) {
                @unlink('public/' . $imageRecentPath);
            }
            $imageRecentPath = $newRecentPath;
        }

        $parameters = [
            'TITULO' => $_POST['titulo'],
            'CONTEUDO' => $_POST['conteudo'],
            'AVALIACAO' => $_POST['rating'],
            'CATEGORIA' => $_POST['categoria'],
            'DATA_EDICAO' => date('Y-m-d H:i:s'),
            'IMAGEM' => $imageFeaturedPath,
            'IMAGEM_RECENT' => $imageRecentPath
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

        if($post && $post->IMAGEM_RECENT && $post->IMAGEM_RECENT !== 'assets/images/default_recents.png' && file_exists('public/' . $post->IMAGEM_RECENT)) {
            @unlink('public/' . $post->IMAGEM_RECENT);
        }

        $database->deleteById('Posts', $id);

        return redirect('admin/listaposts');
    }

    public function storeComment()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }

        $database = App::get('database');
        
        $postId = $_POST['post_id'] ?? null;
        $content = trim($_POST['conteudo'] ?? '');
        $userId = $_SESSION['user']->ID;

        if ($postId && !empty($content)) {
            $parameters = [
                'POST_ID' => $postId,
                'USER_ID' => $userId,
                'CONTEUDO' => $content,
                'DATA_CRIACAO' => date('Y-m-d H:i:s')
            ];

            $database->insert('Comentarios', $parameters);
        }

        if ($postId) {
            return redirect("post?id={$postId}");
        }
        
        return redirect('posts');
    }

    public function updateComment()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }

        $database = App::get('database');
        $id = $_POST['id'];
        $postId = $_POST['post_id'];
        $conteudo = $_POST['conteudo'];
        $userId = $_SESSION['user']->ID;
        $isAdmin = $_SESSION['user']->IS_ADMIN;

        $comment = $database->findById('Comentarios', $id);

        if ($comment && ($comment->USER_ID == $userId || $isAdmin == 1)) {
            $parameters = [
                'CONTEUDO' => $conteudo,
            ];
            $database->update('Comentarios', $id, $parameters);
        }

        return redirect("post?id={$postId}");
    }

    public function deleteComment()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }

        $database = App::get('database');
        $id = $_POST['id'];
        $postId = $_POST['post_id'];
        $userId = $_SESSION['user']->ID;
        $isAdmin = $_SESSION['user']->IS_ADMIN;

        $comment = $database->findById('Comentarios', $id);

        if ($comment && ($comment->USER_ID == $userId || $isAdmin == 1)) {
            $database->deleteById('Comentarios', $id);
        }

        return redirect("post?id={$postId}");
    }

    public function toggleLike()
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $database = App::get('database');
        $userId = $_SESSION['user']->ID;
        $data = json_decode(file_get_contents('php://input'), true);
        $postId = $data['post_id'] ?? null;

        if (!$postId) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Post ID required']);
            return;
        }

        if ($database->hasUserLikedPost($userId, $postId)) {
            $database->removeLike($userId, $postId);
            $liked = false;
        } else {
            $database->insert('Curtidas', [
                'USER_ID' => $userId,
                'POST_ID' => $postId
            ]);
            $liked = true;
        }

        $newCount = $database->getPostLikes($postId);

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'liked' => $liked, 'count' => $newCount]);
        exit;
    }
}