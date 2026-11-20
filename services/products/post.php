<?php

// image will be base64 encoded, and we store it as 
// assume for now that all fields are available
function createProduct($data){

    $conn = require BASE_PATH . '/db.php';

    // https://stackoverflow.com/questions/15821532/get-current-auto-increment-value-for-any-table
    // use this to delete cache, otherwise AUTO_INCREMENT stays the same
    $statement = $conn->prepare('SET SESSION information_schema_stats_expiry = 0');
    $statement->execute();
    $result = $statement->fetch();


    // https://stackoverflow.com/questions/1405393/finding-the-next-available-id-in-mysql
    $statement = $conn->prepare('SELECT Auto_increment FROM information_schema.tables WHERE table_name="products"');
    $statement->execute();
    $result = $statement->fetch();

    // next id that is available, so that image can be named and stored on disk so the link can be added in the DB
    $next_id = $result['AUTO_INCREMENT'];
    $image_path = BASE_PATH . "/images/" . $next_id;
    $file_w = fopen($image_path, 'w+');
    fwrite($file_w, $data['image']);
    fclose($file_w);

    // maybe some checks later on? has writting been successfull?
    // echo "image path: " . $image_path;

    // add image_path so it can be stored in the db
    echo $image_path;
    $data['image_path'] = $image_path;
    unset($data['image']);
    
    $statement = $conn->prepare("INSERT INTO products (name, description, price, image_path, availability_date, is_in_stock) VALUES (:name, :description, :price, :image_path, :availability_date, :is_in_stock)");
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
// https://stackoverflow.com/questions/37462777/mysql-blob-image-too-big-on-website
// this link will explain how to save images to drive, apparently blob can be too big


createProduct($data);