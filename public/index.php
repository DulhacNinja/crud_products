<?php


// $db to run queries, $router to dispatch
require '../bootstrap.php';

$products = $db->query("select * from products", [])->get();

 foreach($products as $product):
     echo var_dump($product['name']) . "<br>";
 endforeach;

$path=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($path);
