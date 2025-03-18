<?php
session_start();
require_once('../db.php');  // Подключаем базу данных
header('Content-Type: application/json');  // Указываем, что будем работать с JSON

// Логируем входящие данные для дебага
file_put_contents("debug_log.txt", file_get_contents("php://input") . PHP_EOL, FILE_APPEND);

// Получаем JSON-данные
$data = json_decode(file_get_contents("php://input"), true);

// Проверяем, что данные получены
if (!$data || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "error" => "Пожалуйста, введите email и пароль."]);
    exit();
}

$email = trim($data['email']);
$password = trim($data['password']);

// Проверяем пользователя в БД
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(["success" => false, "error" => "Неверный email или пароль."]);
        exit();
    }

    // Устанавливаем сессию
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['is_admin'] = $user['is_admin'];

    // Проверяем, что сессия установлена
    if (isset($_SESSION['user_id'])) {
        echo json_encode(["success" => true, "message" => "Успешный вход!", "redirect" => "index.php"]);
    } else {
        echo json_encode(["success" => false, "error" => "Не удалось установить сессию."]);
    }
} catch (PDOException $e) {
    // Логируем ошибку в файл и возвращаем сообщение
    file_put_contents('login_error.log', 'Database error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "error" => "Ошибка базы данных: " . $e->getMessage()]);
}
