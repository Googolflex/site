<?php
session_start();
require_once('../db.php');
header('Content-Type: application/json');

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Пользователь не авторизован']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Получаем данные пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Получаем заказы пользователя
        $ordersStmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ?");
        $ordersStmt->execute([$user_id]);
        $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

        $orderDetails = [];
        foreach ($orders as $order) {
            $orderId = $order['id'];
            // Получаем детали каждого заказа, исправлено имя таблицы
            $orderItemsStmt = $pdo->prepare("SELECT oi.*, p.name as product_name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
            $orderItemsStmt->execute([$orderId]);
            $orderItems = $orderItemsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Добавляем детали в массив
            $orderDetails[] = [
                'order' => $order,
                'items' => $orderItems
            ];
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'username' => $user['username'],
                'email' => $user['email'],
                'is_admin' => $user['is_admin'],
                'orders' => $orderDetails
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Пользователь не найден']);
    }
} catch (PDOException $e) {
    file_put_contents('profile_error.log', 'Database error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'error' => 'Ошибка базы данных']);
}
