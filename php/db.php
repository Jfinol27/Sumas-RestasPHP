<?php
// Configuración de la conexión a la base de datos para el proyecto
$host = 'localhost';
$db   = 'grup_proyectopw'; // Cambia si tu base de datos tiene otro nombre
$user = 'grup_proyectopw';
$pass = 'ale3112';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
