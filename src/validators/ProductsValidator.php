<?php

namespace src\validators;

class ProductsValidator
{

    public function validateID($id): ?string
    {
        if ($id === null or (gettype($id) == 'integer' and $id > 0)) {
            return null;
        }
        return "Product ID must be positive non-zero integer! (OR be null)";
    }

    public function validateName($name): ?string
    {
        if ($name === null || $name == "") {
            return "Product name cannot be empty nor null! (required)";
        }
        if (gettype($name) != "string") {
            return "Product name must be a string! (required)";
        }
        if (strlen($name) > MAX_STR_LENGTH) {
            return "Product name length must be less than 255 characters! (required)";
        }
        return null;
    }

    public function validateDescription($description): ?string
    {
        if ($description === null || $description == "") {
            return null;
        }
        if (gettype($description) != "string") {
            return "Product description must be a string! (OR be null)";
        }
        if (strlen($description) > MAX_STR_LENGTH) {
            return "Product description length must be less than 255 characters! (OR be null)";
        }
        return null;
    }
    // https://www.php.net/manual/en/function.exif-imagetype.php
    // check if the given file is jpg or png
    public function validateImage($image): ?string
    {
        $error = "Image must be valid base 64 string of a JPG or PNG! (OR be null)";

        if ($image == null) {
            return null;
        }
        if (gettype($image) != "string"){
            return $error;
        }
        $bin = base64_decode($image);
        if (is_png($bin) or is_jpeg($bin)){
            return null;
        }
        return $error;
    }

    // check if I can convert availabilityDate
    // https://www.php.net/manual/en/function.strtotime.php
    public function validateAvailabilityDate($availabilityDate): ?string
    {
        $error = "Availability date must be a string convertable to DateTime, must be valid calendar date (YYYY-MM-DD)! (OR be null)";
        if($availabilityDate === null){
            return null;
        }
        if(gettype($availabilityDate) != 'string'){
            return $error;
        }

        if (validateDate($availabilityDate) === false) {
            return $error;
        }
        return null;
    }

    public function validatePrice($price): ?string
    {
        if (gettype($price) == 'integer' and $price > 0) {
            return null;
        }
        return "Product price must be a positive non-zero integer! (required)";
    }

    public function validateIsInStock($isInStock): ?string
    {
        if($isInStock === null){
            return null;
        }

        if (gettype($isInStock) == 'boolean') {
            return null;
        }
        return "State of product in stock must be a boolean! (OR be null)";
    }

}