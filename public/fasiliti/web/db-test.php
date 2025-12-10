<?php
$host = '127.0.0.1';
$db   = 'fasiliti';
$user = 'root';
$pass = 'Ci45t@45etD8';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "? PHP connected to MySQL successfully!";
} catch (\PDOException $e) {
    echo "? Connection failed: " . $e->getMessage();
}