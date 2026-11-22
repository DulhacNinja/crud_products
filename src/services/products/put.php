<?php

// https://www.geeksforgeeks.org/php/building-a-rest-api-with-php-and-mysql/
function updateProductByID($data){

    $conn = require BASE_PATH . '/db.php';

    // also make sure that /images exists in the first place
    $image_path = BASE_PATH . "/images/" . $data["id"];
    $file_w = fopen($image_path, 'w+');
    fwrite($file_w, $data['image']);
    fclose($file_w);

    // maybe some checks later on? has writting been successfull?
    // echo "image path: " . $image_path;

    // add image_path so it can be stored in the db
    echo $image_path;
    $data['image_path'] = $image_path;
    unset($data['image']);
    
    $statement = $conn->prepare("UPDATE products SET 
        name=:name, description=:description, price=:price, image_path=:image_path,
        availability_date=:availability_date, is_in_stock=:is_in_stock WHERE id = :id");
    
    $statement->execute($data);
    $result = $statement->fetch();

    $jsonformat = json_encode($result);
    return $jsonformat;
}

// 'true' means return as assoc arr
$data = json_decode(file_get_contents('php://input'), true);

// if an image exists, convert it from b64 to blob
if (isset($data['image']) && $data['image'] != ''){
    $image = base64_decode($data['image']);
    if(!$image){
        echo '{"error" : "Failed to decode image!"}';
        die(400);
    }
    // replace b64 image with binary blob so it can be stored in DB
    $data['image'] = $image;    
}

// 'true' means return as assoc arr
$data = json_decode(file_get_contents('php://input'), true);

// if an image exists, convert it from b64 to blob
if (isset($data['image']) && $data['image'] != ''){
    $image = base64_decode($data['image']);
    if(!$image){
        echo '{"error" : "Failed to decode image!"}';
        die(400);
    }
    // replace b64 image with binary blob so it can be stored in DB
    $data['image'] = $image;    
}

echo updateProductByID($data);