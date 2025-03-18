<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(["error" => "Нет доступа"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Метод запроса должен быть POST"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id']) || !isset($data['is_admin'])) {
    echo json_encode(["error" => "Некорректные данные"]);
    exit;
}

$user_id = $data['user_id'];
$is_admin = $data['is_admin'] ? 1 : 0;

$query = $pdo->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
if ($query->execute([$is_admin, $user_id])) {
    echo json_encode(["success" => "Роль пользователя обновлена"]);
} else {
    echo json_encode(["error" => "Ошибка при обновлении"]);
}
?>
