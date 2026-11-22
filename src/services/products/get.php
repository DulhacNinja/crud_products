<?php
// !!!!!!!!!!!!!!!!!!!!!! IMPORTANT TODO:
//!!!!! I need to create a "product" object so that I can automate the process of converting from image path to image b64

// these should functions should be added in a "repositories" directory
function getProductByID(int $id){

    $conn = require BASE_PATH . '/db.php';

    $statement = $conn->prepare("SELECT * FROM products WHERE id = :id");

    $statement->execute(['id' => $id]);
    $result = $statement->fetch();

    $jsonformat = json_encode($result);

    return $jsonformat;
}

function getProductPage(int $page, int $limit){

    $conn = require BASE_PATH . '/db.php';

    $start_from = ($page -1) * $limit;

    $statement = $conn->prepare("SELECT * FROM products LIMIT ? OFFSET ?");

    // if I try to add the assoc array in the "execute" call it converts the ints to strings => SQL syntax error
    $statement->bindParam(1, $limit, PDO::PARAM_INT);
    $statement->bindParam(2, $start_from, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll();
    $jsonformat = json_encode($result);
    return $jsonformat;

}

$id = $_GET["id"];
$page = $_GET["page"];
$limit = $_GET["limit"];


// if id is present, that means we want a single product
// we need some proper data validation firstly
if(isset($id)){
    $id = intval($id);
    $result = getProductByID($id);

    // if product doesn't exist, it returns false
    if ($result){
        echo $result;
    }
    else{
        echo "{\"error\": \"Product with id = {$id} does not exist!\"}";
    }
}

// if id is not present, we want a page
elseif (isset($page) && $page > 0 && isset($limit) && $limit > 0){
    $page = intval($page);
    $limit = intval($limit);

    $result = getProductPage($page, $limit);

    // if no products are found, I return a string which contains an empty list
    if ($result == "[]"){
        echo "{\"error\": \"Products with page = {$page} and limit = {$limit} do not exist!\"}";
    }
    else{
        echo $result;
    }
}
