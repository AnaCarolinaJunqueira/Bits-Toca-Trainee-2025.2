<?php

namespace App\Controllers;
use App\Controllers\ExampleController;
use App\Core\Router;

$router->get('', 'LoginController@index');
$router->get('dashboard', 'DashboardController@index');
$router->get('login', 'LoginController@index');
$router->post('login', 'LoginController@login');
$router->post('logout', 'DashboardController@logout');