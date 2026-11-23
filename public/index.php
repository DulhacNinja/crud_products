<?php

    const BASE_PATH = __DIR__ . '/..';
    const MAX_STR_LENGTH = 255;

    require BASE_PATH . '/vendor/autoload.php';
    require BASE_PATH . '/src/utils.php';

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    $router = require BASE_PATH . '/src/init_router.php';
    $router->route($uri, $method);

    