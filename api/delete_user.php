<?php
require_once('../db.php');

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_GET['id'])) {
        echo json_encode(["success" => false, "error" => "Не указан ID пользователя"]);
        exit();
    }

    $user_id = intval($_GET['id']);

    // Проверяем, что нельзя удалить себя
    session_start();
    if ($_SESSION['user_id'] == $user_id) {
        echo json_encode(["success" => false, "error" => "Нельзя удалить самого себя"]);
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Ошибка при удалении"]);
    }
}
?>
