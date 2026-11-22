<?php

namespace src\models;

// require BASE_PATH . "/utils.php";

use DateTime;
use Exception;

class Product
{
    // the product object can be valid with a null id, cause that means it just wasn't stored
    private $id;

    // name must be valid, not null, less than 255 length
    private $name;

    // valid price, positive non 0 integer, represents "cents"
    private $price;

    // less than 255 length or null, not mandatory
    private $description;

    // I'll store it as base64, since that's how I'm getting it from the HTTP POST json body
    private $image;

    // not sure if should save it as string or datetime, not mandatory
    private $availabilityDate;

    // just a bool, not mandatory
    private $isInStock;

    public function __construct(array $data)
    {
        $errors = [];

        if (Product::validateID($data["id"])) {
            $this->id = $data["id"];
        } else {
            $errors[] = "ID must be positive non-zero integer (OR be null, theorically valid state)";
        }
        if (Product::validateName($data["name"])) {
            $this->name = $data["name"];
        } else {
            $errors[] = "Name must a string, non-empty, non-null, and at most " . MAX_STR_LENGTH . " chars (mandatory)";
        }
        if (Product::validatePrice($data["price"])) {
            $this->price = $data["price"];
        } else {
            $errors[] = "Price must be a positive non-zero integer (mandatory)";
        }
        if (Product::validateDescription($data["description"])) {
            $this->description = $data["description"];
        } else {
            $errors[] = "Description can be a string at most " . MAX_STR_LENGTH . " chars long (OR be null, not mandatory)";
        }
        if (Product::validateAvailabilityDate($data["availability_date"])) {
            $this->availabilityDate = $data["availability_date"];
        } else {
            $errors[] = "Availability date must be convertable to DateTime, must be valid calendar date (YYYY-MM-DD) (OR be null, not mandatory)";
        }
        if (Product::validateImage($data["image"])) {
            $this->image = $data["image"];
        } else {
            $errors[] = "Image must be valid base64 string of a JPG or PNG (OR be null, not mandatory)";
        }
        if (Product::validateIsInStock($data["is_in_stock"])) {
            $this->isInStock = $data["is_in_stock"];
        } else {
            $errors[] = "Is_In_Stock must be valid boolean(OR be null, not mandatory)";
        }

        if (!empty($errors)) {
            foreach ($errors as $error){
                echo $error . "\n";
            }
            http_response_code(400);
            die();
        }

    }

    public static function validateID($id): bool
    {
        // id should be either null (object not yet stored) or positive non 0 integer
        if (gettype($id) == 'NULL') {
            return true;
        }
        if (gettype($id) == 'integer' and $id > 0) {
            return true;
        }
        return false;
    }

    public static function validateName($name): bool
    {
        // name is mandatory!
        if ($name == null || $name == "") {
            return false;
        }
        if (gettype($name) != "string") {
            return false;
        }
        return validateStringLength($name);
    }

    public static function validateDescription($description): bool
    {
        if ($description == null || $description == "") {
            return true;
        }
        if (gettype($description) != "string") {
            return false;
        }
        return validateStringLength($description);
    }
    // https://www.php.net/manual/en/function.exif-imagetype.php
    // check if the given file is jpg or png
    public static function validateImage($image): bool
    {
        if ($image == null) {
            return true;
        }
        $bin = base64_decode($image);
        return (is_png($bin) or is_jpeg($bin));
    }

    // check if I can convert availabilityDate
    // https://www.php.net/manual/en/function.strtotime.php
    public static function validateAvailabilityDate($availabilityDate): bool
    {
        if (validateDate($availabilityDate) === false) {
            return false;
        }
        return true;
    }

    public static function validatePrice($price): bool
    {
        if (gettype($price) == 'integer' and $price > 0) {
            return true;
        }
        return false;
    }

    public static function validateIsInStock($isInStock): bool
    {
        if (gettype($isInStock) == 'boolean') {
            return true;
        }
        return false;
    }

    public function setID($id)
    {
        if (Product::validateID($id) === false) {
            throw new Exception('"ID must be positive non-zero integer (OR be null, theorically valid state)');
        }
        $this->id = $id;
    }
    public function getID()
    {
        return $this->id;
    }
    public function setName($name)
    {
        if (Product::validateName($name) === false) {
            throw new Exception("Name must a string, non-empty, non-null, and at most " . MAX_STR_LENGTH . " chars (mandatory)");
        }
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setDescription($description)
    {
        if (Product::validateDescription($description) === false) {
            throw new Exception("Description can be a string at most " . MAX_STR_LENGTH . " chars long (OR be null, not mandatory");
        }
        $this->description = $description;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setPrice($price): void
    {
        if (Product::validatePrice($price) === false) {
            throw new Exception("Price must be a positive non-zero integer (mandatory)");
        }
        $this->price = $price;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function setAvailabilityDate($availabilityDate){
        if (Product::validateAvailabilityDate($availabilityDate) === false) {
            throw new Exception("Availability date must be convertable to DateTime, must be valid calendar date (YYYY-MM-DD) (OR be null, not mandatory)");
        }
        $this->availabilityDate = new DateTime($availabilityDate);
    }
    public function getAvailabilityDate(): DateTime{
        return $this->availabilityDate;
    }
    public function setImage($image){
        if (Product::validateImage($image) === false) {
            throw new Exception("Image must be valid base 64 string of a JPG or PNG (OR be null, not mandatory)");
        }
        else{
            $this->image = $image;
        }
    }
    public function getImage(){
        return $this->image;
    }
    public function setIsInStock($isInStock){
        if (Product::validateIsInStock($isInStock) === false) {
            throw new Exception("Is_In_Stock must be valid boolean(OR be null, not mandatory)");
        }
        $this->isInStock = $isInStock;
    }
    public function getIsInStock(){
        return $this->isInStock;
    }
}