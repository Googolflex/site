<?php
require_once('../db.php');
header("Content-Type: application/json");

// Запрос для получения всех уникальных типов товаров
$stmt = $pdo->prepare("SELECT DISTINCT product_type FROM products");
$stmt->execute();

$productTypes = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($productTypes);
exit();
?>