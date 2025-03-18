// /api/order.php
<?php
header('Content-Type: application/json');
require_once '../db.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['user_id'], $data['user_name'], $data['address'], $data['payment_method'])) {
        $userId = $data['user_id'];
        $userName = $data['user_name'];
        $address = $data['address'];
        $paymentMethod = $data['payment_method'];

        // Получаем все товары в корзине
        $stmt = $pdo->prepare("SELECT c.*, p.price FROM cart_items c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
        $stmt->execute([$userId]);
        $cartItems = $stmt->fetchAll();

        if (empty($cartItems)) {
            echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
            exit();
        }

        // Вычисляем общую сумму заказа
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Сохраняем заказ в таблицу orders
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
        $stmt->execute([$userId, $totalPrice]);
        $orderId = $pdo->lastInsertId();

        // Очищаем корзину после оформления заказа
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $stmt->execute([$userId]);

        echo json_encode([
            'status' => 'success',
            'order_id' => $orderId,
            'total_price' => $totalPrice,
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    }
}
?>
