<?php

// Permitir CORS para desarrollo
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Autoload de Composer
require __DIR__ . '/../vendor/autoload.php';

// Cargar configuración de base de datos
require __DIR__ . '/../config/database.php';

// Cargar rutas
$router = require __DIR__ . '/../routes.php';

// Despachar la solicitud
$router->dispatch();
