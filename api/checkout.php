<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=UTF-8');

session_start();
require '../db.php';  // Подключаем файл с настройками БД

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Метод запроса должен быть POST"]);
    exit;
}

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Пользователь не авторизован"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Проверяем, есть ли данные о доставке пользователя
$delivery_query = $pdo->prepare("SELECT * FROM delivery WHERE user_id = ?");
$delivery_query->execute([$user_id]);
$delivery_data = $delivery_query->fetch(PDO::FETCH_ASSOC);

if (!$delivery_data) {
    echo json_encode(["error" => "Необходимо заполнить данные для доставки"]);
    exit;
}

// Проверяем корзину пользователя
$query = $pdo->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
$query->execute([$user_id]);
$cart_items = $query->fetchAll(PDO::FETCH_ASSOC);

if (!$cart_items) {
    echo json_encode(["error" => "Корзина пуста"]);
    exit;
}

// Подсчитываем сумму заказа
$total_price = 0;
foreach ($cart_items as $item) {
    $product_query = $pdo->prepare("SELECT price, stock FROM products WHERE id = ?");
    $product_query->execute([$item['product_id']]);
    $product = $product_query->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(["error" => "Товар не найден"]);
        exit;
    }

    // Проверяем наличие товара на складе
    if ($product['stock'] < $item['quantity']) {
        echo json_encode(["error" => "Недостаточно товара на складе для ID " . $item['product_id']]);
        exit;
    }

    $total_price += $product['price'] * $item['quantity'];
}

// Создаём заказ
$order_query = $pdo->prepare("INSERT INTO orders (user_id, total_price, created_at) VALUES (?, ?, NOW())");
if (!$order_query->execute([$user_id, $total_price])) {
    echo json_encode(["error" => "Ошибка при создании заказа"]);
    exit;
}

$order_id = $pdo->lastInsertId();  // Получаем ID только что созданного заказа

// Переносим товары из `cart` в `order_items` и обновляем `stock`
foreach ($cart_items as $item) {
    $product_query = $pdo->prepare("SELECT price, stock FROM products WHERE id = ?");
    $product_query->execute([$item['product_id']]);
    $product = $product_query->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Уменьшаем количество товара на складе
        $new_stock = $product['stock'] - $item['quantity'];
        $update_stock_query = $pdo->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $update_stock_query->execute([$new_stock, $item['product_id']]);

        // Добавляем товар в order_items
        $order_item_query = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $order_item_query->execute([$order_id, $item['product_id'], $item['quantity'], $product['price']]);
    }
}

// Очищаем корзину после оформления заказа
$delete_cart_query = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
$delete_cart_query->execute([$user_id]);

echo json_encode(["success" => "Заказ успешно оформлен"]);
exit;
?>
