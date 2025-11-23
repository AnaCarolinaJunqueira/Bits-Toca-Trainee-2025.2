<?php

namespace App\Controllers;
use App\Controllers\ExampleController;
use App\Core\Router;

$router->get('admin/listausuarios', 'UsuarioController@index');

$router->get('admin/listausuarios/store', 'UsuarioController@store');

$router->get('admin/listausuarios/update', 'UsuarioController@update');

$router->get('admin/listausuarios/delete', 'UsuarioController@delete');

$router->get('', 'UsuarioController@index');

$router->post('admin/listausuarios/store', 'UsuarioController@store');

$router->post('admin/listausuarios/update', 'UsuarioController@update');

$router->post('admin/listausuarios/delete', 'UsuarioController@delete');