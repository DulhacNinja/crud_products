<?php

use src\Router;
use src\controllers\ProductsController;

$router = new Router();

// crud products endpoint, returns json
$router->get("/api/products", ProductsController::class);
$router->post("/api/products", ProductsController::class);
$router->delete("/api/products", ProductsController::class);
$router->put("/api/products", ProductsController::class);

$router->get("/", BASE_PATH . "/views/index.php");

return $router;