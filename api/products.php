<?php
require_once('../db.php');
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // Получаем параметры
    $sort = $_GET['sort'] ?? 'default';
    $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
    $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 999999999;
    $manufacturers = isset($_GET['manufacturers']) ? explode(',', $_GET['manufacturers']) : [];
    $product_types = isset($_GET['product_types']) ? explode(',', $_GET['product_types']) : [];

    // Параметры сортировки
    $orderBy = "";
    switch ($sort) {
        case 'price_asc':
            $orderBy = "ORDER BY price ASC";
            break;
        case 'price_desc':
            $orderBy = "ORDER BY price DESC";
            break;
        case 'name_asc':
            $orderBy = "ORDER BY name ASC";
            break;
        case 'name_desc':
            $orderBy = "ORDER BY name DESC";
            break;
        default:
            $orderBy = "";
    }

    // Формируем условие для производителей
    $manufacturerCondition = "";
    if (!empty($manufacturers)) {
        $placeholders = implode(',', array_fill(0, count($manufacturers), '?'));
        $manufacturerCondition = "AND manufacturer IN ($placeholders)";
    }

    $productTypeCondition = "";
    if (!empty($product_types)) {
        $placeholders = implode(',', array_fill(0, count($product_types), '?'));
        $productTypeCondition = "AND product_type IN ($placeholders)";
    }

    $stmt = $pdo->prepare("SELECT * FROM products WHERE price BETWEEN ? AND ? $manufacturerCondition $productTypeCondition $orderBy");

    $params = [$min_price, $max_price];
    if (!empty($manufacturers)) {
        $params = array_merge($params, $manufacturers);
    }
    if (!empty($product_types)) {
        $params = array_merge($params, $product_types);
    }

    $stmt->execute($params);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($products);
    exit();
}

if ($method == 'POST' || $method == 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data) {
        echo json_encode(["success" => false, "error" => "Invalid JSON"]);
        exit();
    }

    $name = $data['name'] ?? null;
    $manufacturer = $data['manufacturer'] ?? null;
    $product_type = $data['product_type'] ?? null;
    $description = $data['description'] ?? null;
    $price = $data['price'] ?? null;
    $image = $data['image'] ?? null;

    if (!$name || !$price || !$manufacturer || !$product_type) {
        echo json_encode(["success" => false, "error" => "Missing required fields"]);
        exit();
    }

    if ($method == 'POST') {
        $stmt = $pdo->prepare("INSERT INTO products (name, manufacturer, product_type, description, price, image) VALUES (?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$name, $manufacturer, $product_type, $description, $price, $image]);
    } else {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(["success" => false, "error" => "Missing ID"]);
            exit();
        }
        $stmt = $pdo->prepare("UPDATE products SET name = ?, manufacturer = ?, product_type = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $success = $stmt->execute([$name, $manufacturer, $product_type, $description, $price, $image, $id]);
    }

    echo json_encode(["success" => $success]);
    exit();
}

if ($method == 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        echo json_encode(["success" => false, "error" => "Missing ID"]);
        exit();
    }
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $success = $stmt->execute([$id]);

    echo json_encode(["success" => $success]);
    exit();
}
?>
