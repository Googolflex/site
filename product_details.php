<?php
session_start();
require 'db.php';

$is_admin = isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1;
$user_id = $_SESSION['user_id'] ?? null;
$profile_text = "Профиль";
if (!isset($_SESSION['user_id'])) {
    $profile_text = "Вход";
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Некорректный ID товара.");
}

$product_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT id, name, description, price, image, stock FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Товар не найден.");
    }
} catch (PDOException $e) {
    die("Ошибка базы данных: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="styles/product_details.css">
</head>
<body>

<header>
        <div class="header-container">
            <a href="index.php" id = "catalog">Каталог</a>
            <a href="aboutus.php" id = "aboutus">О нас</a>
            <a href="cart.php" id="cart">Корзина</a>
            <a href="profile.php" id="profile"><?php echo $profile_text ?></a>
            <?php if ($is_admin): ?>
                <a href="admin.php" class="admin-link">Админ панель</a>
            <?php endif; ?>
        </div>
    </header>

<main>
    <div class="container">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <div class="horizontal_container">
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            <p id="desc"><?= htmlspecialchars($product['description']) ?></p>
            <div class="price-buy-container">
                <p><strong>Цена:</strong> <span class="product-price"><?= number_format($product['price'], 0) ?> ₽</span></p>
                <p>Остаток на складе: <?= number_format($product['stock'], 0) ?></p>
                <?php if ($product['stock'] > 0): ?>
                    <button onclick="addToCart(<?= $product['id'] ?>)">Добавить в корзину</button>
                <?php else: ?>
                    <p class="out-of-stock">Нет в наличии</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<footer>
        <div class="footer-container">
        <div class="problem-info">
            <h3>Столкнулись с проблемой?</h3>
            <p>Если вы столкнулись с проблемой или у вас возникли вопросы, наша команда всегда готова помочь!</p>
            <p>Не стесняйтесь обращаться к нам по любым вопросам:</p>
            <ul>
                <p><span class="highlight">Электронная почта:</span> <a href="mailto:support@example.com">support@example.com</a></p>
                <p><span class="highlight">Телефон:</span> <a href="tel:+1234567890">+1 234 567 890</a></p>
            </ul>
            <p>Мы постараемся ответить на ваш запрос в кратчайшие сроки. Благодарим за использование нашего сервиса!</p>
        </div>
        <div class="social-links">
                <h3>Контакты</h3>
                <a href="https://vk.com/googolflex"><i></i> VKontakte</a>
                <a href="https://t.me/@googolflex"><i></i> Telegram</a>
                <a href="https://github.com/Googolflex"><i></i> Git</a>
                <a href="https://discord.com/invite/esldota"><i></i> Discord</a>
            </div>

            <div class="location">
                <h3>Наш адрес</h3>
                <p>г.Новосибирск, Советский район, микрорайон Академгородок, улица Кутателадзе 3</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2025 googolflex</p>
        </div>

    </footer>

<script>
    function addToCart(productId) {
        fetch('api/cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Товар добавлен в корзину!');
            } else {
                alert('Ошибка: ' + data.error);
            }
        })
        .catch(error => alert('Ошибка запроса: ' + error));
    }

    async function searchProducts() {
        let query = document.getElementById('search-input').value.toLowerCase();
        let response = await fetch('api/products.php');
        let products = await response.json();
        let filteredProducts = products.filter(product =>
            product.name.toLowerCase().includes(query) ||
            product.description.toLowerCase().includes(query)
        );
        let container = document.getElementById('search-results');
        container.innerHTML = "";
        if (filteredProducts.length === 0) {
            container.innerHTML = "<p>Товары не найдены</p>";
        } else {
            filteredProducts.forEach(product => {
                let div = document.createElement('div');
                div.className = 'search-item';
                div.innerHTML = `
                    <a href="product_details.php?id=${product.id}">
                        <img src="${product.image}" alt="${product.name}">
                        <h3>${product.name}</h3>
                        <p>${product.price} руб.</p>
                    </a>
                `;
                container.appendChild(div);
            });
        }
    }
</script>

</body>
</html>
