<?php

    $user = 'root';
    $pass = 'root';
    $config = [
        'host' => 'localhost',
        'port' =>   '3306',
        'dbname' => 'crud_products_db',
        'charset' => 'utf8mb4'
    ];

    $dsn = 'mysql:' . http_build_query($config, '', ';');

    return $conn = new \PDO($dsn, $user, $pass, [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC]);