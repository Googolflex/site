<?php
header('Content-Type: application/json');
require '../db.php';

// Убедимся, что сессия запущена
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Проверяем права администратора
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

// Получаем данные из тела запроса
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Проверяем обязательные поля
if (
    !$data ||
    !isset($data['name']) ||
    !isset($data['manufacturer']) ||   // Добавлено поле manufacturer
    !isset($data['product_type']) ||
    !isset($data['description']) ||
    !isset($data['price']) ||
    !isset($data['image']) ||
    !isset($data['stock'])
) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

try {
    // Добавляем новый товар
    $stmt = $pdo->prepare('
        INSERT INTO products (name, manufacturer, product_type, description, price, image, stock)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $data['name'],
        $data['manufacturer'],  // Добавлено поле manufacturer
        $data['product_type'],
        $data['description'],
        $data['price'],
        $data['image'],
        $data['stock']
    ]);

    echo json_encode(['success' => true, 'message' => 'Товар успешно добавлен']);
} catch (PDOException $e) {
    file_put_contents('add_product_error.log', 'Database error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'error' => 'Database error']);
}