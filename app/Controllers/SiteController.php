<?php

namespace App\Controllers;

use App\Core\App;

class SiteController
{
    public function index()
    {
        $database = App::get('database');

        $featuredPosts = $database->selectFeaturedPosts(3);

        $recentPosts = $database->selectRecentPosts(18);

        return view('site/landing_page', [
            'featuredPosts' => $featuredPosts,
            'recentPosts' => $recentPosts
        ]);
    }

    public function posts()
    {
        $database = App::get('database');
        $limit = 6;
        $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $searchTerm = trim($_GET['search'] ?? '');
        $category = trim($_GET['category'] ?? '');

        $offset = ($page - 1) * $limit;

        $posts = $database->selectPublicPosts($limit, $offset, $searchTerm, $category);
        $totalPosts = $database->countPublicPosts($searchTerm, $category);
        $totalPages = ceil($totalPosts / $limit);

        return view('site/post_page', [
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'searchTerm' => $searchTerm,
            'currentCategory' => $category
        ]);
    }

    public function showPost()
    {
        $database = App::get('database');
        $id = $_GET['id'] ?? null;

        if (!$id) {
            return redirect('posts');
        }

        $post = $database->getPostWithAuthor($id);

        if (!$post) {
            return redirect('posts');
        }

        $likesCount = $database->getPostLikes($id);
        $userLiked = false;
        if(isset($_SESSION['user'])) {
            $userLiked = $database->hasUserLikedPost($_SESSION['user']->ID, $id);
        }

        $comments = $database->getPostComments($id);

        return view('site/individual_post', [
            'post' => $post,
            'likesCount' => $likesCount,
            'userLiked' => $userLiked,
            'comments' => $comments
        ]);
    }
}