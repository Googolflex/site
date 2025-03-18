<?php
require_once('../db.php');
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $stmt = $pdo->query("SELECT id, username, email, is_admin FROM users");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
}

echo json_encode(["error" => "Метод не поддерживается"]);
?>
