<?php
// Подключение к базе данных
require_once('../db.php');

// Получаем ID товара
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(['message' => 'Товар успешно удален']);
} else {
    echo json_encode(['error' => 'ID товара не указан']);
}
?>
