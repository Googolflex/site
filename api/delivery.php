<?php
require_once('../db.php');
header('Content-Type: application/json');

session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['error' => 'Пользователь не авторизован']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

// Получение данных доставки
if ($method == 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM delivery WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $delivery = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($delivery) {
        echo json_encode(['success' => true, 'data' => $delivery]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Данные для доставки не найдены']);
    }
    exit();
}

// Обновление данных доставки
if ($method == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $city = $data['city'];
    $street = $data['street'];
    $phone = $data['phone'];
    $postal_code = $data['postal_code'];

    // Проверка на обязательные поля
    if (empty($first_name) || empty($last_name) || empty($city) || empty($street) || empty($phone) || empty($postal_code)) {
        echo json_encode(['error' => 'Все поля должны быть заполнены']);
        exit();
    }

    // Обновляем или вставляем данные
    $stmt = $pdo->prepare("SELECT id FROM delivery WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $existingDelivery = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingDelivery) {
        // Обновляем
        $stmt = $pdo->prepare("UPDATE delivery SET first_name = ?, last_name = ?, city = ?, street = ?, phone = ?, postal_code = ? WHERE user_id = ?");
        $success = $stmt->execute([$first_name, $last_name, $city, $street, $phone, $postal_code, $user_id]);
    } else {
        // Вставляем новые данные
        $stmt = $pdo->prepare("INSERT INTO delivery (user_id, first_name, last_name, city, street, phone, postal_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$user_id, $first_name, $last_name, $city, $street, $phone, $postal_code]);
    }

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Ошибка при сохранении данных']);
    }
    exit();
}
?>
