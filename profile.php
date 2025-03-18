<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$is_admin = isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1;
$profile_text = "Профиль";
if (!isset($_SESSION['user_id'])) {
    $profile_text = "Вход";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link rel="stylesheet" href="styles/profile.css">
    <style>
        .orders-table {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .orders-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .orders-table th, .orders-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .total-sum {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
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
        <div class="horizontal-container" id="spacer-con">
            <div class="container" id="delivery-data">
                <h2>Данные для доставки</h2>

                <div class="error-message" id="error-message"></div>
                <div class="success-message" id="success-message"></div>

                <form id="delivery-form">
                    <div class="form-group">
                        <label for="first_name">Имя:</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Фамилия:</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>

                    <div class="form-group">
                        <label for="city">Город:</label>
                        <input type="text" id="city" name="city" required>
                    </div>

                    <div class="form-group">
                        <label for="street">Улица и дом:</label>
                        <input type="text" id="street" name="street" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Номер телефона:</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="postal_code">Почтовый индекс:</label>
                        <input type="number" id="postal_code" name="postal_code" required>
                    </div>

                    <button type="submit">Сохранить</button>
                </form>
            </div>

            <div class="container" id="profile-data">
            <div class="horizontal-container" id="profile-label_con">
            <h1>Профиль</h1>
            <div class="button-container">
                <a href="admin.php" class="admin-panel-btn" id="admin-panel-btn" style="display: none;">Режим администратора</a>
                <a href="api/logout.php" class="logout-btn">⍈</a>
            </div>
            </div>
                <div id="profile-info" class="profile-info"></div>
                <h2>Ваши заказы</h2>
                <div id="orders-table" class="orders-table"></div>
                <div class="total-sum" id="total-sum"></div>
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
    document.addEventListener('DOMContentLoaded', function () {
        const profileInfo = document.getElementById('profile-info');
        const adminPanelBtn = document.getElementById('admin-panel-btn');
        const ordersTable = document.getElementById('orders-table');
        const totalSum = document.getElementById('total-sum');
        const deliveryForm = document.getElementById('delivery-form');

        function loadProfile() {
            fetch('/site/api/profile.php', { method: 'POST', credentials: 'same-origin' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const user = data.data;
                    profileInfo.innerHTML = `
                        <p><strong>Имя:</strong> ${user.username}</p>
                        <p><strong>Email:</strong> ${user.email}</p>
                        <p><strong>Роль:</strong> ${user.is_admin ? 'Администратор' : 'Пользователь'}</p>
                    `;
                    if (user.is_admin) {
                        adminPanelBtn.style.display = 'block';
                    }

                    let totalSpent = 0;
                    let ordersHtml = '';

                    if (user.orders && user.orders.length > 0) {
                        user.orders.forEach(order => {
                            if (order.items && order.items.length > 0) {
                                ordersHtml += '<table><tr><th>Товар</th><th>Количество</th><th>Цена</th></tr>';
                                order.items.forEach(item => {
                                    ordersHtml += `
                                        <tr>
                                            <td>${item.product_name}</td>
                                            <td>${item.quantity}</td>
                                            <td>${(item.price * item.quantity).toFixed(2)}₽</td>
                                        </tr>
                                    `;
                                });
                                ordersHtml += '</table>';
                                totalSpent += parseFloat(order.order.total_price);
                            }
                        });

                        ordersTable.innerHTML = ordersHtml;
                    } else {
                        ordersTable.innerHTML = '<p>У вас нет заказов.</p>';
                    }

                    totalSum.innerHTML = `Общая сумма потраченных средств: ${totalSpent.toFixed(2)}₽`;
                } else {
                    profileInfo.innerHTML = `<p>Ошибка: ${data.error}</p>`;
                }
            })
            .catch(error => {
                console.error('Ошибка сервера:', error);
                profileInfo.innerHTML = '<p>Ошибка сервера. Попробуйте позже.</p>';
            });
        }

        function loadDeliveryData() {
            fetch('/site/api/delivery.php', { method: 'GET', credentials: 'same-origin' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('first_name').value = data.data.first_name;
                    document.getElementById('last_name').value = data.data.last_name;
                    document.getElementById('city').value = data.data.city;
                    document.getElementById('street').value = data.data.street;
                    document.getElementById('phone').value = data.data.phone;
                    document.getElementById('postal_code').value = data.data.postal_code;
                }
            })
            .catch(error => console.error('Ошибка загрузки данных:', error));
        }

        deliveryForm.addEventListener('submit', function (event) {
    event.preventDefault();

            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const city = document.getElementById('city').value.trim();
            const street = document.getElementById('street').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const postalCode = document.getElementById('postal_code').value.trim();

            const nameRegex = /^[А-Яа-яЁёA-Za-z\s-]+$/;
            const cityRegex = /^[А-Яа-яЁёA-Za-z\s-]+$/;
            const streetRegex = /^[А-Яа-яЁёA-Za-z0-9\s,.-]+$/;
            const phoneRegex = /^\+?[0-9]{10,15}$/;
            const postalCodeRegex = /^[0-9]{5,6}$/;

            let errorMessage = "";

            if (!nameRegex.test(firstName)) errorMessage += "Имя должно содержать только буквы.\n";
            if (!nameRegex.test(lastName)) errorMessage += "Фамилия должна содержать только буквы.\n";
            if (!cityRegex.test(city)) errorMessage += "Город должен содержать только буквы.\n";
            if (!streetRegex.test(street)) errorMessage += "Улица должна содержать только буквы, цифры и знаки препинания.\n";
            if (!phoneRegex.test(phone)) errorMessage += "Некорректный номер телефона.\n";
            if (!postalCodeRegex.test(postalCode)) errorMessage += "Почтовый индекс должен содержать 5-6 цифр.\n";

            if (errorMessage) {
                alert(errorMessage);
                return;
            }

            const deliveryData = { first_name: firstName, last_name: lastName, city, street, phone, postal_code: postalCode };

            fetch('http://localhost/ww/api/delivery.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(deliveryData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Данные успешно сохранены');
                } else {
                    alert('Ошибка при сохранении данных');
                }
            })
            .catch(error => console.error('Ошибка при сохранении:', error));
        });


        loadProfile();
        loadDeliveryData();
    });
    </script>

</body>
</html>
