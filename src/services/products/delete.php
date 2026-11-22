<?php

// this will return false despite the fact that it successfully runs, weird
// maybe I should firstly check if it already exists?
// cause what if I want to delete an item that doesn't exist
// good enough for now, but I need to refactor this
function deleteProductByID(int $id){

    $conn = require BASE_PATH . '/db.php';

    $statement = $conn->prepare("DELETE FROM products WHERE id = :id");

    $statement->execute(['id' => $id]);
    $result = $statement->fetch();

    $jsonformat = json_encode($result);
    // dd($jsonformat);
    return $jsonformat;
}

$id = $_GET["id"];
if(isset($id)){
    $id = intval($id);
    $result = deleteProductByID($id);
    echo $result;
    echo "{\"message\": \"Product with id = {$id} has been successfully deleted!\"}";
}else{
    echo "{\"error\": \"ID required to delete a product\"}";
}