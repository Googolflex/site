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
    <title>Вход</title>
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
        <h1>Вход</h1>
        <form id="login-form">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Введите email" required>
            </div>
            <div class="input-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" placeholder="Введите пароль" required>
            </div>
            <button type="submit">Войти</button>
        </form>
        <p>Нет аккаунта? <a href="register.php" class="link">Зарегистрироваться</a></p>
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
        document.getElementById('login-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, password }),
                    credentials: 'same-origin'
                });

                const resultText = await response.text();
                console.log('Ответ сервера:', resultText);

                try {
                    const result = JSON.parse(resultText);
                    if (result.success) {
                        alert(result.message);
                        window.location.href = document.referrer;
                    } else {
                        alert(result.error);
                    }
                } catch (e) {
                    console.error('Ошибка при парсинге JSON:', e);
                    alert('Получен неправильный ответ от сервера');
                }

            } catch (error) {
                console.error('Ошибка сервера:', error);
                alert('Произошла ошибка при авторизации. Попробуйте позже.');
            }
        });
    </script>
</body>
</html>
