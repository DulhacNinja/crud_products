<?php

namespace src\repositories;
use src\models\Product;

class ProductRepository
{

    private \PDO $conn;
    public function __construct()
    {
        $this->conn = require BASE_PATH . '/src/init_db.php';
    }
    public function getPagesNumber(int $limit): array|null{
        
        $statement = $this->conn->prepare("SELECT count(1) FROM products");
        $statement->execute();
        $result = $statement->fetch();
        if (empty($result)){
            return null;
        }
        $rows = $result["count(1)"];
        // echo $rows;
        $pages = ceil($rows / $limit);
        return ["pages" => $pages, "prodCount" => $rows];
    }

    public function getByID(int $id): Product|null
    {

        $statement = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $statement->execute(['id' => $id]);
        $result = $statement->fetch();
        if (empty($result)) {
            return null;
        }
        // if the product has an image path, the associated base 64 image will be returned, otherwise null
        $result['image'] = $this->resolveImagePath($result['imagePath']);
        return new Product($result);

    }
    // naive pagination
    public function getPage(int $page, int $limit)
    {

        $start_from = ($page - 1) * $limit;
        $statement = $this->conn->prepare("SELECT * FROM products LIMIT ? OFFSET ?");

        // if I try to add the assoc array in the "execute" call it converts the ints to strings => SQL syntax error
        $statement->bindParam(1, $limit, \PDO::PARAM_INT);
        $statement->bindParam(2, $start_from, \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetchAll();
        $products = [];
        foreach ($result as $prod) {
            $prod["image"] = $this->resolveImagePath($prod["imagePath"]);
            $products[] = new Product($prod);
        }
        return $products;
    }

    public function delete(int $id){

        // returns true if the product didn't exist at all
        // returns false if deletion fails
        // return deleted product if it's been found and deleted

        // check if product exists at all
        $prod = $this->getByID($id);
        if ($prod === null) {
            return true;
        }

        // delete it's image
        $imagePath = BASE_PATH . "/images/" . $id;
        unlink($imagePath);

        // delete db entry
        $statement = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        $statement->execute(['id' => $id]);

        // https://www.php.net/manual/en/function.mysql-error.php
        $result = $statement->errorCode();
        
        if (!$result) {
            return false;
        }
        return $prod;

    }

    public function save(Product $product): Product|null
    {

        $next_id = $this->getNextID();
        $imagePath = BASE_PATH . "/images/" . $next_id;

        // save image to file system, will set path to null if no image is provided
        $imagePath = $this->saveImageToFile($product->getImage(), $imagePath);

        // lowk kinda crazy you can just cast this and it works???
        $data = (array) $product;

        // replace 'image' with 'imagePath'
        $data['imagePath'] = $imagePath;

        // can't let this in the params array, it will cause sql error since it's not used in query
        unset($data['id']);
        unset($data['image']);

        $statement = $this->conn->prepare("
        INSERT INTO products (
            name, description, price, imagePath, 
            availabilityDate, isInStock
        ) 
        VALUES 
            (
                :name, :description, :price, :imagePath, 
                :availabilityDate, :isInStock
            )
        ");

        $statement->execute($data);
        $result = $statement->fetch();
        if ($result) {
            unlink($imagePath);
            return null;
        }

        return $this->getByID($next_id);

    }
    public function update(Product $new_prod): Product|null
    {

        $id = $new_prod->getId();

        // firstly, let's check if the product exists at all
        $curr_prod = $this->getByID($id);
        if ($curr_prod === null) {
            return null;
        }

        $data = (array) $new_prod;
        $imagePath = BASE_PATH . "/images/{$id}";

        // check if images are different
        if ($curr_prod->getImage() !== $new_prod->getImage()) {

            // delete previous picture
            unlink($imagePath);
            
            // if the new picture is null, it won't write anything in place of old picture
            $this->saveImageToFile($new_prod->getImage(), $imagePath);

        }
        // replace 'image' with 'imagePath'
        $data['imagePath'] = $imagePath;
        unset($data['image']);
        // unset($data['id']);

        // i could probably make this more fine, only update fields that are different
        $statement = $this->conn->prepare("
        UPDATE 
            products 
        SET 
            name = :name, 
            description = :description, 
            price = :price, 
            imagePath = :imagePath, 
            availabilityDate = :availabilityDate, 
            isInStock = :isInStock 
        WHERE 
            id = :id
        ");
        $statement->execute($data);
        $result = $statement->fetch();
        
        if ($result) {
            return null;
        }
        return $this->getByID($id);
    }


    // we are assuming all images are stored in the file system in BASE_PATH./images/{prod_id}
    // inside the DB we shall store the relative path
    // https://stackoverflow.com/questions/38859621/reading-binary-files-in-php

    public function resolveImagePath($imagePath)
    {

        // If I can't get the filesize it probably doesn't exist??
        $filesize = filesize($imagePath);
        if (!$filesize) {
            return null;
        }
        // open file for reading in binary mode
        $fp = fopen($imagePath, 'rb');
        if (!$fp) {
            return null;
        }

        $bin = fread($fp, filesize($imagePath));
        if (!$bin){
            return null;
        }

        if(!fclose($fp)){
            return null;
        }

        // return image base_64 encoded
        return base64_encode($bin);
    }

    public function saveImageToFile($imgBase64, $imagePath): string|null
    {
        // if image is null, return null back
        if ($imgBase64 === null) {
            return null;
        }
        // also make sure that /images exists in the first place
        if (!is_dir(BASE_PATH . "/images")) {
            mkdir(BASE_PATH . "/images");
        }
        // in theory speaking I should bother to check if all operations are successfully done
        // but in practice it's fine since it's my computer and I don't have permissions issues :D
        $bin = base64_decode($imgBase64);
        $file_w = fopen($imagePath, 'w+');
        if ($file_w == false){
            return null;
        }

        if (fwrite($file_w, $bin) == false){
            return null;
        };
        if(!fclose($file_w)){
            return null;
        }

        return $imagePath;
    }

    public function getNextID()
    {

        // https://stackoverflow.com/questions/15821532/get-current-auto-increment-value-for-any-table
        // use this to delete cache, otherwise AUTO_INCREMENT stays the same
        $statement = $this->conn->prepare('SET SESSION information_schema_stats_expiry = 0');
        $statement->execute();
        $result = $statement->fetch();


        // https://stackoverflow.com/questions/1405393/finding-the-next-available-id-in-mysql
        $statement = $this->conn->prepare('SELECT Auto_increment FROM information_schema.tables WHERE table_name="products"');
        $statement->execute();
        $result = $statement->fetch();

        // next id that is available, so that image can be named and stored on disk so the link can be added in the DB
        $next_id = $result['AUTO_INCREMENT'];
        return $next_id;
    }

}