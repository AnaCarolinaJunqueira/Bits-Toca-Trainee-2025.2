<?php

namespace App\Controllers;
use App\Controllers\ExampleController;
use App\Core\Router;

$router->get('admin/listaposts', 'PostController@index');

$router->get('admin/listaposts/store', 'PostController@store');

$router->get('admin/listaposts/update', 'PostController@update');

$router->get('admin/listaposts/delete', 'PostController@delete');

$router->get('', 'SiteController@index');

$router->get('posts', 'SiteController@posts');

$router->post('admin/listaposts/store', 'PostController@store');

$router->post('admin/listaposts/update', 'PostController@update');

$router->post('admin/listaposts/delete', 'PostController@delete');

$router->get('forum', 'ForumController@index');

$router->get('forum/discussion', 'ForumController@show');

$router->post('forum/discussion/store', 'ForumController@storeDiscussion');

$router->post('forum/discussion/update', 'ForumController@updateDiscussion');

$router->post('forum/discussion/delete', 'ForumController@deleteDiscussion');

$router->post('forum/reply/delete', 'ForumController@deleteReply');

$router->post('forum/reply/store', 'ForumController@storeReply');

$router->get('login', 'LoginController@index');

$router->post('login', 'LoginController@login');

$router->get('logout', 'LoginController@logout');

$router->get('admin/dashboard', 'LoginController@dashboard');

$router->post('post/comment/store', 'PostController@storeComment');

$router->post('post/comment/update', 'PostController@updateComment');

$router->post('post/comment/delete', 'PostController@deleteComment');

$router->post('post/like', 'PostController@toggleLike');


$router->get('admin/listausuarios', 'UserController@index');

$router->get('admin/listausuarios/store', 'UserController@store');

$router->get('admin/listausuarios/update', 'UserController@update');

$router->get('admin/listausuarios/delete', 'UserController@delete');

$router->post('admin/listausuarios/store', 'UserController@store');

$router->post('admin/listausuarios/update', 'UserController@update');

$router->post('admin/listausuarios/delete', 'UserController@delete');
$router->get('post', 'PostController@show');

$router->get('post/like', 'PostController@like');
