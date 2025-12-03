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
}