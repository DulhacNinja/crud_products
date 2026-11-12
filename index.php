<?php

 require 'utils.php';
 require 'Database.php';
// echo phpinfo(INFO_ALL);
// die();
 $db = new Database("localhost", "3306", "crud_products_db", "root", "root");
 $products = $db->query("select * from products", []);

 foreach($products as $product):
     echo var_dump($product['name']) . "<br>";
 endforeach;
 die();