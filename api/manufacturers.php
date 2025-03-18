<?php
require_once('../db.php');
header("Content-Type: application/json");

$stmt = $pdo->query("SELECT DISTINCT manufacturer FROM products");
$manufacturers = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($manufacturers);
exit();
?>