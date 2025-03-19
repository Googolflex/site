<?php
session_start();
$is_admin = isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1;
$user_id = $_SESSION['user_id'] ?? null;
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
    <title>"Все по 300"</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function formatPrice(price) {
            return Math.floor(price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
        }

        async function loadProducts() {
    let sort = document.getElementById('sort').value;
    let query = document.getElementById('search-input').value.toLowerCase();
    let minPrice = document.getElementById('min-price').value || 0;
    let maxPrice = document.getElementById('max-price').value || 999999999;

    let selectedManufacturers = [];
    let manufacturerCheckboxes = document.querySelectorAll('input[name="manufacturer"]:checked');
    manufacturerCheckboxes.forEach(checkbox => {
        selectedManufacturers.push(checkbox.value);
    });

    let selectedProductTypes = [];
    let productTypeCheckboxes = document.querySelectorAll('input[name="product_type"]:checked');
    productTypeCheckboxes.forEach(checkbox => {
        selectedProductTypes.push(checkbox.value);
    });

    let url = `api/products.php?sort=${sort}&min_price=${minPrice}&max_price=${maxPrice}&query=${query}`;
    
    if (selectedManufacturers.length > 0) {
        url += `&manufacturers=${selectedManufacturers.join(',')}`;
    }
    
    if (selectedProductTypes.length > 0) {
        url += `&product_types=${selectedProductTypes.join(',')}`;
    }

    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error('Ошибка ответа от сервера');
        }

        let products = await response.json();

        let container = document.getElementById('products');
        container.innerHTML = "";

        if (products.length === 0) {
            container.innerHTML = "<p>Товары не найдены</p>";
        } else {
            products.forEach(product => {
                let div = document.createElement('div');
                div.className = 'product-card';
                div.innerHTML = `
                    <img src="${product.image}" alt="${product.name}" class="product-image" onclick="window.location.href='product_details.php?id=${product.id}'">
                    <h3><a href="product_details.php?id=${product.id}">${product.name}</a></h3>
                    <p>${product.description}</p>
                    <b>${formatPrice(product.price)} руб.</b>
                `;
                container.appendChild(div);
            });
        }
    } catch (error) {
        console.error('Ошибка загрузки товаров:', error);
    }
}

async function loadManufacturers() {
    let response = await fetch('api/manufacturers.php');
    let manufacturers = await response.json();

    let manufacturerFiltersDiv = document.getElementById('manufacturers-con');
    manufacturers.forEach(manufacturer => {
        let label = document.createElement('label');
        label.innerHTML = `
            <div id="manufactuter-con" class="filter-con">
                ${manufacturer} <input type="checkbox" name="manufacturer" value="${manufacturer}" onchange="loadProducts()"> 
            </div>
        `;
        manufacturerFiltersDiv.appendChild(label);
    });
}

async function loadProductTypes() {
    let response = await fetch('api/product_types.php');
    let productTypes = await response.json();

    let productTypesDiv = document.getElementById('product-types-con');
    productTypes.forEach(productType => {
        let label = document.createElement('label');
        label.innerHTML = `
            <div class="filter-con">
                <label>${productType}</label>
                <input type="checkbox" name="product_type" value="${productType}" onchange="loadProducts()"> 
            </div>
        `;
        productTypesDiv.appendChild(label);
    });
}


        function showDetails(productId) {
            window.location.href = `product_details.php?id=${productId}`;
        }

        window.onload = () => {
            loadProducts();
            loadManufacturers();  // Загружаем список производителей при загрузке страницы
            loadProductTypes();
        };
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
    
    

    <div class="container">
    <div class="sidebar">
        <select id="sort" onchange="loadProducts()">
            <option value="">Сортировка</option>
            <option value="price_asc">Цена (по возрастанию)</option>
            <option value="price_desc">Цена (по убыванию)</option>
            <option value="name_asc">Название (А-Я)</option>
            <option value="name_desc">Название (Я-А)</option>
        </select>
        <h3>Фильтры</h3>
        <input type="text" id="search-input" placeholder="Поиск по товарам" oninput="loadProducts()">
        <input type="number" id="min-price" placeholder="Мин. цена" oninput="loadProducts()">
        <input type="number" id="max-price" placeholder="Макс. цена" oninput="loadProducts()">

        <h3>Производители</h3>
        <div id="manufacturers-con" class="filters-continer"></div>
        
        <h3>Типы товаров</h3>
        <div id="product-types-con" class="filters-continer"></div>

    </div>
        <main>
            <h1>Каталог</h1>
            <div id="products" class="product-container"></div>
        </main>
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


</body>
</html>
