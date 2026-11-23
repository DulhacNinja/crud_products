<?php

namespace src\services;
use src\models\Product;
use src\repositories\ProductRepository;
use Exception;

class ProductsService{

    private ProductRepository $productRepository;

    public function __construct(){

        $this->productRepository = new ProductRepository();
    }

    // should return a proper message if product is not found
    public function getByID(int $id): Product | null{

        $prod = $this->productRepository->getByID($id);
        if($prod === null){
            throw new Exception("Product with id {$id} does not exist!");
        }

        return $prod;
    }

    // the array is empty if no elements are found for that page
    public function getPage(int $page, int $limit): array{

        $prods = $this->productRepository->getPage($page, $limit);
        if(empty($prods)){
            throw new Exception("Page #{$page} of size {$limit} is not valid or is empty!");
        }
        return $prods;

    }

    public function delete(int $id){

        $deleted_prod = $this->productRepository->delete($id);
        if($deleted_prod === true){
            throw new Exception("Product with ID {$id} doesn't exist at all!");
        }
        if($deleted_prod === false){
            throw new Exception("Failed to delete product with ID {$id}");
        }
        return $deleted_prod;
    }

    public function save(Product $product){

        $new_prod = $this->productRepository->save($product);
        if($new_prod === null){
            throw new Exception("Failed to save product wtih name {$product->getName()}");
        }
        return $new_prod;
    }

    public function update(Product $product){

        $updated_prod = $this->productRepository->update($product);
        if($updated_prod === null){
            throw new Exception("Failed to update product with ID {$product->getID()}");
        }
        return $updated_prod;
    }
    

}
