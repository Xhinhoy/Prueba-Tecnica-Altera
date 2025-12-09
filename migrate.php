<?php

require __DIR__ . '/vendor/autoload.php';

$capsule = require __DIR__ . '/config/database.php';

// Crear directorio de base de datos si no existe
$dbDir = __DIR__ . '/database';
if (!file_exists($dbDir)) {
    mkdir($dbDir, 0755, true);
}

// Crear archivo de base de datos si no existe
$dbFile = __DIR__ . '/database/items.sqlite';
if (!file_exists($dbFile)) {
    touch($dbFile);
    echo "Base de datos SQLite creada.\n";
}

// Ejecutar migraciones
$migrationsPath = __DIR__ . '/database/migrations';
$migrations = glob($migrationsPath . '/*.php');

foreach ($migrations as $migration) {
    $migrationData = require $migration;
    echo "Ejecutando migración: " . basename($migration) . "\n";
    $migrationData['up']();
    echo "Migración completada: " . basename($migration) . "\n";
}

echo "\nTodas las migraciones se ejecutaron correctamente.\n";
