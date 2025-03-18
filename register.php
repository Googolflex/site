<?php
session_start();
$is_admin = isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1;
$user_id = $_SESSION['user_id'] ?? null;
$profile_text = "Профиль";
if (!isset($_SESSION['user_id'])) {
    $profile_text = "Вход";
}
if (isset($_SESSION['user_id'])) {
    header('Location: aboutus.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles/auth.css">
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

    <div class="container">
        <h1>Регистрация</h1>

        <form id="register-form">
            <div class="input-group">
                <label for="username">Имя</label>
                <input type="text" id="username" placeholder="Введите имя" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Введите email" required>
            </div>
            <div class="input-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" placeholder="Введите пароль" required>
            </div>
            <button type="submit">Зарегистрироваться</button>
        </form>

        <p>Есть аккаунт? <a href="login.php" class="link">Войти</a></p>
    </div>

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

    <script>
        document.getElementById('register-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const response = await fetch('api/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, email, password })
            });

            const result = await response.json();

            if (result.error) {
                alert(result.error);
            } else if (result.message) {
                alert(result.message);
                window.location.href = "login.php";
            }
        });
    </script>
</body>
</html>
