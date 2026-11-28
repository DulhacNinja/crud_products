<?php

use src\Router;
use src\controllers\ProductsController;

$router = new Router();

// crud products endpoint, returns json
$router->get("/api/products", ProductsController::class);
$router->post("/api/products", ProductsController::class);
$router->delete("/api/products", ProductsController::class);
$router->put("/api/products", ProductsController::class);

// html views
$router->get("/", BASE_PATH . "/views/index.php");
$router->get('/edit', BASE_PATH . "/views/edit.php");
$router->get("/show", BASE_PATH . "/views/show.php");

return $router;