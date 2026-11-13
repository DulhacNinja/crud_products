<?php

require __DIR__ . "/vendor/autoload.php";

$db_config = [
    'host' => 'localhost',
    'port' =>   '3306',
    'dbname' => 'crud_products_db',
    'charset' => 'utf8mb4'
];

$db = new \core\Database($db_config, 'root', 'root');

// add URL PATHS to router
$router = new \core\Router();
$router->add('GET','/',[\controllers\MyController::class, 'home']);
$router->add('GET', '/about', [\controllers\MyController::class, 'about']);
