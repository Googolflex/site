<?php
session_start();
require '../db.php'; // Подключение к БД

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Вы не авторизованы"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Добавление в корзину (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['product_id']) || !isset($input['quantity']) || $input['quantity'] <= 0) {
        echo json_encode(["error" => "Некорректные данные"]);
        exit;
    }

    $product_id = (int)$input['product_id'];
    $quantity = (int)$input['quantity'];

    $stmt = $pdo->prepare("SELECT stock, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(["error" => "Товар не найден"]);
        exit;
    }

    if ($product['stock'] < $quantity) {
        echo json_encode(["error" => "Недостаточно товара на складе"]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        $new_quantity = $cart_item['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$new_quantity, $cart_item['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }

    echo json_encode(["success" => "Товар добавлен в корзину"]);
    exit;
}

// Получение корзины (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("
        SELECT cart.id AS cart_id, cart.product_id, cart.quantity, 
               products.name, products.price, products.image, products.stock
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cart_items);
    exit;
}

// Удаление товара из корзины (DELETE)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['cart_id'])) {
        echo json_encode(["error" => "ID товара в корзине не передан"]);
        exit;
    }

    $cart_id = (int)$input['cart_id'];

    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);

    echo json_encode(["success" => "Товар удален из корзины"]);
    exit;
}

// **Обновление количества товара (PUT)**
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['cart_id']) || !isset($input['quantity']) || $input['quantity'] <= 0) {
        echo json_encode(["error" => "Некорректные данные"]);
        exit;
    }

    $cart_id = (int)$input['cart_id'];
    $new_quantity = (int)$input['quantity'];

    // Проверяем, существует ли товар в корзине
    $stmt = $pdo->prepare("SELECT product_id FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart_item) {
        echo json_encode(["error" => "Товар в корзине не найден"]);
        exit;
    }

    // Проверяем, есть ли достаточно товара на складе
    $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->execute([$cart_item['product_id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product['stock'] < $new_quantity) {
        echo json_encode(["error" => "Недостаточно товара на складе"]);
        exit;
    }

    // Обновляем количество товара в корзине
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->execute([$new_quantity, $cart_id]);

    echo json_encode(["success" => "Количество товара обновлено"]);
    exit;
}

echo json_encode(["error" => "Неверный запрос"]);
exit;

?>
