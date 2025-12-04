<?php

date_default_timezone_set('America/Sao_Paulo');

return [
    'database' => [
        'name' => 'teste_db',
        'username' => 'root',
        'password' => '',
        'connection' => 'mysql:host=127.0.0.1',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ]
];