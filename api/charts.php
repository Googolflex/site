<?php
require_once('../db.php');

$response = ['popular_products' => [], 'avg_order_price' => [], 'orders_per_day' => []];

// Популярные товары (по количеству заказов)
$sql = "SELECT p.name, SUM(oi.quantity) as total_quantity 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        GROUP BY p.name 
        ORDER BY total_quantity DESC 
        LIMIT 10";
$stmt = $pdo->query($sql);
$response['popular_products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Средний чек пользователей
$sql = "SELECT u.username, ROUND(AVG(o.total_price), 2) as avg_spent 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        GROUP BY u.username";
$stmt = $pdo->query($sql);
$response['avg_order_price'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Количество заказов по дням
$sql = "SELECT DATE(created_at) as order_date, COUNT(*) as order_count 
        FROM orders 
        GROUP BY order_date 
        ORDER BY order_date ASC";
$stmt = $pdo->query($sql);
$response['orders_per_day'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($response);
