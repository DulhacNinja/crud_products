<?php

    // Start the session
    session_start();

    const BASE_PATH = __DIR__ . '/..';
    require BASE_PATH . '/utils.php';

    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // parse_url returns false if url can't be parsed
    if(!$url) {
        echo 'Malformed url';
        die(400);
    }

    // '/hello/' and '/hello' should mean the same thing
    $url = normalizePath($url);

    // first element is always empty
    $url_arr = explode('/', $url);
    array_shift($url_arr);

    // first dir of the path
    $resource = array_shift($url_arr);
    $action = array_shift($url_arr);

    $_SESSION['resource'] = $resource;
    $_SESSION['action'] = $action;

    switch($resource) {
        // this is the '/' url path
        case '':
            require BASE_PATH .'/views/home.php';
            break;

        case 'products':
        // the default should be to display the list
            if(!isset($_SESSION['action']) || $_SESSION['action'] == '') {
                $_SESSION['action'] = 'show';
            }
            require BASE_PATH .'/controllers/products.php';
            break;

        default:
            echo '<h1> this resource does not exist! try /products</h1>';
            var_dump($url);
            die(400);
    
}