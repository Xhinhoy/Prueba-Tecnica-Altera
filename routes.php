<?php

use Nicolas\ItemsApi\Router;
use Nicolas\ItemsApi\Controllers\ItemController;

$router = new Router();

// Rutas de la API
$router->get('/items', [ItemController::class, 'index']);
$router->get('/items/{id}', [ItemController::class, 'show']);
$router->post('/items', [ItemController::class, 'store']);
$router->put('/items/{id}', [ItemController::class, 'update']);
$router->delete('/items/{id}', [ItemController::class, 'destroy']);

return $router;
