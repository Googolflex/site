<?php
session_start();
$is_admin = isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1;
$profile_text = "Профиль";
$is_logged_in = isset($_SESSION['user_id']);
if (!$is_logged_in) {
    $profile_text = "Вход";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>"Все по 300". Корзина</title>
    <link rel="stylesheet" href="styles/cart.css">

    <script>
        
        var isLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;

        async function loadCart() {
            let response = await fetch('api/cart.php');
            let cart = await response.json();
            let container = document.getElementById('cart-items');
            let totalPrice = 0;
            container.innerHTML = "";

            if (!isLoggedIn) {
                container.innerHTML = "<p class='empty-cart'>Вы не авторизованы</p>";
                document.getElementById('order-details').style.display = 'none';
                document.getElementById('total-price').style.display = 'none';
                document.querySelector('.checkout-btn').style.display = 'none';
                return;
            }

            if (cart.length === 0) {
                container.innerHTML = "<p class='empty-cart'>Корзина пуста</p>";
                document.getElementById('order-details').style.display = 'none';
                document.getElementById('total-price').style.display = 'none';
                document.querySelector('.checkout-btn').style.display = 'none';
            } else {
                cart.forEach(item => {
                    let div = document.createElement('div');
                    div.className = 'cart-item';
                    let itemTotalPrice = Math.floor(item.price * item.quantity);
                    totalPrice += itemTotalPrice;

                    div.innerHTML = `
                        <img src="${item.image}" alt="${item.name}" class="cart-image">
                        <div class="cart-details">
                            <div class="horizontal_container">
                                <h3>${item.name}</h3>
                                <button class="remove-btn" onclick="removeFromCart(${item.cart_id})">X</button>
                            </div>
                            <form class="quantity-form" oninput="return updateQuantity(${item.cart_id}, this.querySelector('input[type=number]').value)">
                                <label for="quantity">Количество:</label>
                                <input type="number" value="${item.quantity}" min="1" step="1" required>
                            </form>
                            <label>Остаток на складе: ${item.stock}</label>
                            <div class="horizontal_container">
                                <p>Цена за шт: <b>${Math.floor(item.price)} руб.</b></p>
                                <p class="total-price"><b>${itemTotalPrice} руб.</b></p>
                            </div>
                        </div>
                    `;
                    container.appendChild(div);
                });

                document.getElementById('total-price').innerHTML = `Итоговая сумма: <b>&nbsp;${totalPrice} руб.</b>`;
                document.querySelector('.checkout-btn').style.display = 'block';
            }
        }

        async function removeFromCart(cartId) {
            let response = await fetch('api/cart.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_id: cartId })
            });
            loadCart();
        }

        async function updateQuantity(cartId, newQuantity) {
            if (!newQuantity || newQuantity <= 0) {
                alert("Количество должно быть больше 0");
                return false;
            }

            let response = await fetch('api/cart.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_id: cartId, quantity: parseInt(newQuantity) })
            });

            let result = await response.json();

            if (result.success) {
                loadCart();
            } else {
                alert(result.error || "Произошла ошибка");
            }

            return false;
        }

        async function submitOrder(event) {
        event.preventDefault(); 

        try {
            let response = await fetch('api/checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({})
            });

            let text = await response.text();
            console.log("Ответ сервера:", text); 
            let result = JSON.parse(text); 

            if (result.success) {
                alert("Спасибо за заказ!\n" + result.success + "." + "\nМы свяжемся с вами позже для доставки.");
                location.reload();
            } else {
                alert(result.error || "Ошибка при оформлении заказа");
            }
        } catch (error) {
            console.error("Ошибка запроса:", error);
            alert("Ошибка сети или сервера");
        }
    }

        window.onload = loadCart;
    </script>
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
        <div id="cart-items" class="cart-container"></div>
        <div class="order-details" id="order-details">
            <div id="total-price" class="total-price"></div>
            <button class="checkout-btn" onclick="submitOrder(event)" style="display: none;">Оформить заказ</button>
        </div>
    </main>

    <footer>
        <div class="footer-container">
        <div class="problem-info">
            <h3>Столкнулись с проблемой?</h3>
            <p>Если вы столкнулись с проблемой или у вас возникли вопросы, наша команда всегда готова помочь!</p>
            <p>Не стесняйтесь обращаться к нам по любым вопросам:</p>
            <ul>
                <p><strong>Электронная почта:</strong> <a href="mailto:support@example.com">support@example.com</a></p>
                <p><strong>Телефон:</strong> <a href="tel:+1234567890">+1 234 567 890</a></p>
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

</body>
</html>