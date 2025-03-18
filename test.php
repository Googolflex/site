<?php
require 'db.php';

if ($conn) {
    echo "✅ Подключение к базе `shop_db` успешно!";
} else {
    echo "❌ Ошибка подключения!";
}
?>
