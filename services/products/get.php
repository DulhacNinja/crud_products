<?php

// for now let's just return simple json, no pagination
$conn = require BASE_PATH . '/db.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // this is not safe, we are inlining user input, BAD!!!!
    $statement = $conn->prepare("SELECT * FROM products WHERE id =  {$id}");
}
else{
    $statement = $conn->prepare('SELECT * FROM products');
}

$statement->execute();
$result = $statement->fetchAll();

echo $jsonformat = json_encode($result);