<?php

namespace App\Controllers;

use App\Core\App;
use Exception;

class ForumController
{
    public function index()
    {
        $database = App::get('database');
        $limit = 6;
        $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $searchTerm = trim($_GET['search'] ?? '');
        $category = trim($_GET['category'] ?? '');

        $offset = ($page - 1) * $limit;

        $discussions = $database->selectPaginatedDiscussions($limit, $offset, $searchTerm, $category);
        $totalDiscussions = $database->countDiscussions($searchTerm, $category);
        $totalPages = ceil($totalDiscussions / $limit);
        
        $catStats = $database->countCategories();

        return view('site/forum', [
            'discussions' => $discussions,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'searchTerm' => $searchTerm,
            'currentCategory' => $category,
            'catStats' => $catStats
        ]);
    }

    public function show()
    {
        $database = App::get('database');
        $id = $_GET['id'] ?? null;

        if (!$id) {
            redirect('forum');
        }

        $discussion = $database->getDiscussionById($id);
        
        if (!$discussion) {
            redirect('forum');
        }

        $replies = $database->getDiscussionReplies($id);

        return view('site/forum_individual', [
            'discussion' => $discussion,
            'replies' => $replies
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

    public function storeDiscussion()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }

        $database = App::get('database');
        $userId = $_SESSION['user']->ID;
        
        $imagePath = $this->uploadImage('discussao_imagem');

        $parameters = [
            'TITULO' => $_POST['titulo'] ?? '',
            'CONTEUDO' => $_POST['conteudo'] ?? '',
            'CATEGORIA' => $_POST['categoria'] ?? 'Geral',
            'IMAGEM' => $imagePath,
            'AUTOR_ID' => $userId,
            'DATA_POSTAGEM' => date('Y-m-d H:i:s')
        ];

        $database->insert('Discussoes', $parameters);

        return redirect('forum');
    }

    public function updateDiscussion()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }

        $database = App::get('database');
        $discussionId = $_POST['id'];
        $userId = $_SESSION['user']->ID;
        $isAdmin = $_SESSION['user']->IS_ADMIN;
        
        $discussion = $database->getDiscussionById($discussionId);

        if (!$discussion || ($discussion->AUTOR_ID != $userId && $isAdmin != 1)) {
            return redirect("forum/discussion?id={$discussionId}");
        }

        $imagePath = $discussion->IMAGEM;
        $newImagePath = $this->uploadImage('discussao_imagem');
        
        if($newImagePath) {
            if($imagePath && file_exists('public/' . $imagePath)) {
                @unlink('public/' . $imagePath);
            }
            $imagePath = $newImagePath;
        }


        $parameters = [
            'TITULO' => $_POST['titulo'] ?? '',
            'CONTEUDO' => $_POST['conteudo'] ?? '',
            'CATEGORIA' => $_POST['categoria'] ?? $discussion->CATEGORIA,
            'IMAGEM' => $imagePath,
            'DATA_EDICAO' => date('Y-m-d H:i:s')
        ];

        $database->update('Discussoes', $discussionId, $parameters);

        return redirect("forum/discussion?id={$discussionId}");
    }

    public function deleteReply()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }
        
        $database = App::get('database');
        $replyId = $_POST['id'];
        $discussionId = $_POST['discussion_id'];
        $userId = $_SESSION['user']->ID;
        $isAdmin = $_SESSION['user']->IS_ADMIN;

        $reply = $database->findById('Respostas_Discussoes', $replyId);
        
        if ($reply && ($reply->USER_ID == $userId || $isAdmin == 1)) {
            $database->deleteById('Respostas_Discussoes', $replyId);
        }

        return redirect("forum/discussion?id={$discussionId}");
    }
    
    public function storeReply()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }
        
        $database = App::get('database');
        $discussionId = $_POST['discussion_id'] ?? null;
        $content = trim($_POST['conteudo'] ?? '');
        $userId = $_SESSION['user']->ID;

        if ($discussionId && !empty($content)) {
             $parameters = [
                'DISCUSSAO_ID' => $discussionId,
                'USER_ID' => $userId,
                'CONTEUDO' => $content,
                'DATA_CRIACAO' => date('Y-m-d H:i:s')
            ];
            $database->insert('Respostas_Discussoes', $parameters);
        }
        
        if ($discussionId) {
            return redirect("forum/discussion?id={$discussionId}");
        }
        return redirect('forum');
    }
    
    public function deleteDiscussion()
    {
        if (!isset($_SESSION['user'])) {
            return redirect('login');
        }

        $database = App::get('database');
        $discussionId = $_POST['id'];
        $userId = $_SESSION['user']->ID;
        $isAdmin = $_SESSION['user']->IS_ADMIN;

        $discussion = $database->getDiscussionById($discussionId);

        if ($discussion) {
            if ($discussion->AUTOR_ID == $userId || $isAdmin == 1) {
                $database->deleteById('Discussoes', $discussionId);
            }
        }
        
        return redirect('forum');
    }
}