<?php
require_once('../db.php');

header('Content-Type: application/json');

// Получаем JSON-данные
$data = json_decode(file_get_contents("php://input"), true);

// Проверяем, что данные получены
if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["error" => "Пожалуйста, заполните все поля."]);
    exit();
}

$username = trim($data['username']);
$email = trim($data['email']);
$password = trim($data['password']);

// Проверка на имя, оно должно содержать только буквы и пробелы
if (!preg_match("/^[a-zA-Zа-яА-ЯёЁ\s]+$/u", $username)) {
    echo json_encode(["error" => "Имя должно содержать только буквы и пробелы."]);
    exit();
}

// Проверка, не существует ли уже пользователя с таким именем
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->rowCount() > 0) {
    echo json_encode(["error" => "Пользователь с таким именем уже существует."]);
    exit();
}

// Проверка на email
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
    echo json_encode(["error" => "Пользователь с таким email уже существует."]);
    exit();
}

// Хешируем пароль
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Вставляем нового пользователя в базу данных
$stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->execute([$username, $email, $hashed_password]);

echo json_encode(["message" => "Регистрация прошла успешно. Перейдите на страницу входа."]);
