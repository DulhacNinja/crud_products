<?php

use src\models\Product;


// image will be base64 encoded, and we store it as 
// assume for now that all fields are available
function createProduct($data){

    $conn = require BASE_PATH . '/db.php';


    $prod = new Product($data);
    dd($prod);

    


    // maybe some checks later on? has writting been successfull?
    // echo "image path: " . $image_path;

    // add image_path so it can be stored in the db
    echo $image_path;
    $data['image_path'] = $image_path;
    unset($data['image']);
    


    $jsonformat = json_encode($result);
    return $jsonformat;

}
// 'true' means return as assoc arr
$data = json_decode(file_get_contents('php://input'), true);

// // if an image exists, convert it from b64 to blob
// if (isset($data['image']) && $data['image'] != ''){
//     $image = base64_decode($data['image']);
//     if(!$image){
//         echo '{"error" : "Failed to decode image!"}';
//         die(400);
//     }
//     // replace b64 image with binary blob so it can be stored in DB
//     $data['image'] = $image;    
// }

// https://stackoverflow.com/questions/37462777/mysql-blob-image-too-big-on-website
// this link will explain how to save images to drive, apparently blob can be too big
echo createProduct($data);