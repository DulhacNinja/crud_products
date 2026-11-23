<?php

use src\Router;
use src\controllers\ProductsController;

$router = new Router();

$router->get("/api/products", ProductsController::class);
$router->post("/api/products", ProductsController::class);
$router->delete("/api/products", ProductsController::class);
$router->put("/api/products", ProductsController::class);

return $router;