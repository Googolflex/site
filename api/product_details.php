<?php
require '../db.php';
header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'Некорректный ID товара']);
    exit;
}

$product_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT id, name, description, FLOOR(price) AS price, image, stock FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(['success' => false, 'error' => 'Товар не найден']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $product]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Ошибка БД: ' . $e->getMessage()]);
}
?>
