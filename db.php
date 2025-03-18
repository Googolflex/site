<?php
$host = 'localhost';
$dbname = 'm90595s5_shop_db';
$username = 'm90595s5_shop_db';
$password = 'Qwerty123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>
