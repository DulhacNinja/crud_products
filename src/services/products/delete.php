<?php

use src\repositories\ProductRepository;
use src\models\Product;

$prod_repo = new ProductRepository();

$id = $_GET["id"];
if(isset($id)){
    $id = intval($id);
    $result = $prod_repo->deleteByID($id);
    echo "{\"message\": \"Product with id = {$id} has been successfully deleted!\"}";
    echo "{\"error\": \"ID required to delete a product\"}";

    echo $result;
}else{
}
