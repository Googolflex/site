<?php
// Подключение к базе данных
require_once('../db.php');

// Получаем список пользователей
$stmt = $pdo->query("SELECT id, username, email, is_admin FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Выводим список пользователей в формате JSON
echo json_encode($users);
?>
