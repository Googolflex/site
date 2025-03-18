<?php
require_once('../db.php');

// Получаем статистику по товарам
$productQuery = "SELECT p.name, COALESCE(COUNT(oi.product_id), 0) AS quantity 
                 FROM products p
                 LEFT JOIN order_items oi ON p.id = oi.product_id
                 GROUP BY p.id";
$productResult = $pdo->query($productQuery);
$products = $productResult->fetchAll(PDO::FETCH_ASSOC);

// Получаем статистику по пользователям
$userQuery = "SELECT u.username, 
                     COALESCE(COUNT(o.id), 0) AS total_orders, 
                     COALESCE(SUM(o.total_price), 0) AS total_spent
              FROM users u
              LEFT JOIN orders o ON u.id = o.user_id
              GROUP BY u.id";
$userResult = $pdo->query($userQuery);
$users = $userResult->fetchAll(PDO::FETCH_ASSOC);

// Отправляем статистику в ответе
echo json_encode(['products' => $products, 'users' => $users]);
?>
