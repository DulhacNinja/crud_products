<?php

const PRODUCTS_VIEWS = BASE_PATH . '/views/products';
const PRODUCTS_SERVICES = BASE_PATH .'/services/products';

// action that I want to perform as user with view (i.e. create, show, delete)
$action = $_SESSION['action'];

switch ($action) {

    // return view to user with table to display products
    case 'show':
        require PRODUCTS_VIEWS . '/show.php';
        break;
    
    // return view to user with a single product for editing
    case 'edit':
        require PRODUCTS_VIEWS . '/edit.php';
        break;
    
    // return view to user with blank template for creating a new product
    case 'add':
        require PRODUCTS_VIEWS . '/add.php';
        break;
    
    case 'api':
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        // we'll pretend they do the same thing
        if ($method == 'patch'){
            $method = 'put';
        }

        require PRODUCTS_SERVICES . "/{$method}.php";
        break;
    
    default:
        echo '<h1>this action for "products" does not exist!</h1>';
        die(400);

}