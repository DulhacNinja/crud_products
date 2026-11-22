<?php

namespace src\repositories;
use src\models\Product;

class ProductRepository
{

    private \PDO $conn;
    public function __construct()
    {
        $this->conn = require BASE_PATH . '/db.php';
    }

    public function getByID(int $id): Product|null
    {

        $statement = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $statement->execute(['id' => $id]);
        $result = $statement->fetch();

        if (empty($result)) {
            return null;
        }
        // if the product has an image path, the associated base 64 image will be returned
        $result['image'] = $this->resolveImagePath($result['image_path']);
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
            $prod["image"] = $this->resolveImagePath($prod["image_path"]);
            $products[] = new Product($prod);
        }
        return $products;
    }
    public function deleteByID(int $id): Product|null
    {

        // check if product exists at all
        $prod = $this->getByID($id);
        if ($prod === null) {
            echo "Product with id {$id} didn't exist anyway!";
            return null;
        }

        // delete it's image
        $image_path = BASE_PATH . "/images/" . $id;
        unlink($image_path);

        // delete db entry
        $statement = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        $statement->execute(['id' => $id]);

        // https://www.php.net/manual/en/function.mysql-error.php
        $result = $statement->errorCode();
        if ($result !== null) {
            echo "Something went wrong with the deletion.";
            return null;
        }

        echo "Product with id {$id} deleted successfully!";
        return $prod;

    }
    public function save(Product $product): Product|null
    {

        $next_id = $this->getNextID();
        $image_path = BASE_PATH . "/images/" . $next_id;

        // save image to file system,
        $image_path = $this->saveImageToFile($product->getImage(), $image_path);

        // lowk kinda crazy you can just cast this and it works???
        $data = (array) $product;

        // replace 'image' with 'image_path'
        $data['image_path'] = $image_path;
        unset($data['image']);

        $statement = $this->conn->prepare("
        INSERT INTO products (
            name, description, price, image_path, 
            availability_date, is_in_stock
        ) 
        VALUES 
            (
                : name, : description, : price, : image_path, 
                : availability_date, : is_in_stock
            )
        ");
        $statement->execute($data);
        $result = $statement->fetch();

        if ($result !== null) {
            echo "Something went wrong with saving product {$data['name']}";
            unlink($image_path);
            return null;
        }
        echo "Product {$data['name']} has been successfully saved!";
        return $this->getByID($next_id);

    }
    public function update(Product $product): Product|null
    {

        // somehow, the product must have an ID from when it was created before the service
        $id = $product->getId();

        // firstly, let's check if the product exists at all
        $curr_prod = $this->getByID($id);
        if ($curr_prod === null) {
            echo "Product with ID {$id} doesn't exist, please create it first or maybe bad ID";
            return null;
        }

        $data = (array) $product;

        // check if images are different
        if($curr_prod->getImage() !== $product->getImage()) {

            $image_path = BASE_PATH . "/images/{$id}";
            unlink($image_path);
            $this->saveImageToFile($product->getImage(), $image_path);

        }

        // i could probably make this more fine, only update fields that are different
        $statement = $this->conn->prepare("
        UPDATE 
            products 
        SET 
            name =: name, 
            description =: description, 
            price =: price, 
            image_path =: image_path, 
            availability_date =: availability_date, 
            is_in_stock =: is_in_stock 
        WHERE 
            id = : id
        ");

        $statement->execute($data);
        $result = $statement->fetch();
        if ($result !== null) {
            echo "Something went wrong with the update";
            return null;
        }
        return $this->$product;
    }


    // we are assuming all images are stored in the file system in BASE_PATH./images/{prod_id}
    // inside the DB we shall store the relative path
    // https://stackoverflow.com/questions/38859621/reading-binary-files-in-php

    public function resolveImagePath($id)
    {

        $image_path = BASE_PATH . "/images/{$id}";

        // If I can't get the filesize it probably doesn't exist??
        $filesize = filesize($image_path);
        if ($filesize === false) {
            return null;
        }
        // open file for reading in binary mode
        $fp = fopen($image_path, 'rb');
        if ($fp === false) {
            return null;
        }

        $bin = fread($fp, filesize($image_path));
        fclose($fp);

        // return image base_64 encoded
        return base64_encode($bin);
    }

    public function saveImageToFile($ImgBase64, $image_path): string|null
    {

        // if image is null, return null back
        if ($ImgBase64 === null) {
            return null;
        }
        // also make sure that /images exists in the first place
        if (!is_dir(BASE_PATH . "/images")) {
            mkdir(BASE_PATH . "/images");
        }
        // in theory speaking I should bother to check if all operations are successfully done
        // but in practice it's fine since it's my computer and I don't have permissions issues :D
        $file_w = fopen($image_path, 'w+');
        fwrite($file_w, $ImgBase64);
        fclose($file_w);

        return $image_path;
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