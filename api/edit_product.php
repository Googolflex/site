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

// Получаем ID товара из параметров URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid product ID']);
    exit;
}

// Получаем данные из тела запроса
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Проверяем обязательные поля
if (
    !$data ||
    !isset($data['name']) ||
    !isset($data['manufacturer']) ||
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
    // Обновляем товар в базе данных
    $stmt = $pdo->prepare('
        UPDATE products
        SET name = ?, manufacturer = ?, product_type = ?,description = ?, price = ?, image = ?, stock = ?
        WHERE id = ?
    ');
    $stmt->execute([
        $data['name'],
        $data['manufacturer'],
        $data['product_type'],
        $data['description'],
        $data['price'],
        $data['image'],
        $data['stock'],
        $product_id
    ]);

    echo json_encode(['success' => true, 'message' => 'Товар успешно обновлен']);
} catch (PDOException $e) {
    file_put_contents('edit_product_error.log', 'Database error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'error' => 'Database error']);
}