<?php

namespace src\controllers;

use src\services\ProductsService;
use src\models\Product;
use src\validators\ProductsValidator;
use src\StatusCodes;
use Exception;

class ProductsController
{

    private ProductsService $productsService;
    private ProductsValidator $productsValidator;

    public function __construct()
    {
        $this->productsService = new ProductsService();
        $this->productsValidator = new ProductsValidator();
    }

    // wrapper get function
    public function get(){

        if(isset($_GET["id"])){
            $this->getByID();
        }
        else{
            $this->getPage();
        }
    }

    // request to the api endpoint, get method, id in query params
    public function getByID()
    {
        // intval returns 0 on failure
        $id = intval($_GET["id"]);
        if ($id <= 0) {
            http_response_code(StatusCodes::HTTP_BAD_REQUEST);
            echo "{\"error\": \"Product ID must be a positive non-zero integer!\"}";
            die();
        }

        try {
            $product = $this->productsService->getByID($id);
        } catch (Exception $e) {
            http_response_code(StatusCodes::HTTP_NOT_FOUND);
            echo "{ \"error\" : \"{$e->getMessage()}\"}";
            die();
        }

        http_response_code(StatusCodes::HTTP_OK);
        echo json_encode($product, JSON_PRETTY_PRINT);
    }

    public function getPage()
    {

        // page and limit must be positive 
        $page = intval($_GET["page"]);
        $limit = intval($_GET["limit"]);

        if ($page <= 0 or $limit <= 0) {
            http_response_code(StatusCodes::HTTP_BAD_REQUEST);
            echo "{\"error\": \"Page and limit must be a positive non-zero integer!\"}";
            die();
        }

        try {
            $products = $this->productsService->getPage($page, $limit);
        } catch (Exception $e) {
            http_response_code(StatusCodes::HTTP_NOT_FOUND);
            echo "{ \"error\" : \"{$e->getMessage()}\"}";
            die();
        }

        http_response_code(StatusCodes::HTTP_OK);
        echo json_encode($products, JSON_PRETTY_PRINT);

    }

    public function delete()
    {
        // intval returns 0 on failure
        $id = intval($_GET["id"]);
        if ($id <= 0) {
            http_response_code(StatusCodes::HTTP_BAD_REQUEST);
            echo "{\"error\": \"Product ID must be a positive non-zero integer!\"}";
            die();
        }

        try {
            $product = $this->productsService->delete($id);
        } catch (Exception $e) {
            // I should probably change the code if the error is either server sided or user sided
            http_response_code(StatusCodes::HTTP_NOT_FOUND);
            echo "{ \"error\" : \"{$e->getMessage()}\"}";
            die();
        }

        http_response_code(StatusCodes::HTTP_OK);
        // I'm returning it for debugging purposes, because if you return code NO_CONTENT response is forced empty
        echo json_encode($product, JSON_PRETTY_PRINT);
        
    }

    public function post()
    {
        // validate json and fields inside of it
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            http_response_code(StatusCodes::HTTP_BAD_REQUEST);
            echo "{ \"error\" : \"Failed to parse JSON!\"}";
            die();
        }

        // if errors are returned when validating the request body
        $errors = $this->validateRequestBody($data);
        if (!empty($errors)) {
            http_response_code(StatusCodes::HTTP_BAD_REQUEST);
            $msg = implode("\n", $errors);
            echo "{ \"error\" : \"$msg\"}";
            die();
        }
        // create product object
        $product = new Product($data);

        // call product service to store it
        try {
            $product = $this->productsService->save($product);
        } catch (Exception $e) {
            http_response_code(StatusCodes::HTTP_INTERNAL_SERVER_ERROR);
            echo "{ \"error\" : \"{$e->getMessage()}\"}";
            die();
        }
        // product created successfully
        http_response_code(StatusCodes::HTTP_CREATED);
        echo json_encode($product, JSON_PRETTY_PRINT);
    }

    // just overwrite the whole thing (except the image, that would be wasteful)
    public function put()
    {
        // validate json and fields inside of it
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            http_response_code(StatusCodes::HTTP_BAD_REQUEST);
            echo "{ \"error\" : \"Failed to parse JSON!\"}";
            die();
        }

        // if errors are returned when validating the request body
        $errors = $this->validateRequestBody($data);
        if (!empty($errors)) {
            http_response_code(StatusCodes::HTTP_BAD_REQUEST);
            $msg = implode("\n", $errors);
            echo "{ \"error\" : \"$msg\"}";
            die();
        }

        // create product object
        $product = new Product($data);

        // call product service to update it
        try {
            $product = $this->productsService->update($product);
        } catch (Exception $e) {
            http_response_code(StatusCodes::HTTP_INTERNAL_SERVER_ERROR);
            echo "{ \"error\" : \"{$e->getMessage()}\"}";
            die();
        }
        // product created successfully
        http_response_code(StatusCodes::HTTP_OK);
        echo json_encode($product, JSON_PRETTY_PRINT);
    }


    private function validateRequestBody(array $data): array
    {
        $errors = [];

        // if validate function returns a string, it's an error
        // if it returns null, it passed
        $result = $this->productsValidator->validateID($data['id']);
        if ($result !== null){
            $errors[] = $result;
        }
        $result = $this->productsValidator->validateName($data['name']);
        if ($result !== null){
            $errors[] = $result;
        }
        $result = $this->productsValidator->validatePrice($data['price']);
        if ($result !== null){
            $errors[] = $result;
        }
        $result = $this->productsValidator->validateDescription($data['description']);
        if ($result !== null){
            $errors[] = $result;
        }
        $result = $this->productsValidator->validateAvailabilityDate($data['availabilityDate']);
        if ($result !== null){
            $errors[] = $result;
        }
        $result = $this->productsValidator->validateIsInStock($data['isInStock']);
        if ($result !== null){
            $errors[] = $result;
        }
        $result = $this->productsValidator->validateImage($data['image']);
        if ($result !== null){
            $errors[] = $result;
        }
        return $errors;

    }



}