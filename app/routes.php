<?php

namespace App\Controllers;
use App\Controllers\ExampleController;
use App\Core\Router;

$router->get('admin/listaposts', 'PostController@index');

$router->get('admin/listaposts/store', 'PostController@store');

$router->get('admin/listaposts/update', 'PostController@update');

$router->get('admin/listaposts/delete', 'PostController@delete');

$router->get('', 'PostController@index');

$router->post('admin/listaposts/store', 'PostController@store');

$router->post('admin/listaposts/update', 'PostController@update');

$router->post('admin/listaposts/delete', 'PostController@delete');