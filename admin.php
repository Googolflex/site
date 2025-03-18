<?php
session_start();
require_once('db.php');
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit();
}
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
    <title>Админ-панель</title>
    <link rel="stylesheet" href="styles/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
document.addEventListener("DOMContentLoaded", () => {
    loadProducts();
    loadUsers();
    loadStatistics();
    loadCharts();
});




        async function loadCharts() {
            let response = await fetch('api/charts.php');
            let data = await response.json();

            let productNames = data.popular_products.map(p => p.name);
            let productQuantities = data.popular_products.map(p => p.total_quantity);
            new Chart(document.getElementById('popularProductsChart'), {
        type: 'bar',
        data: {
            labels: productNames,
            datasets: [{
                label: 'Количество заказов',
                data: productQuantities,
                backgroundColor: '#601cff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#e0e0e0'
                    }
                },
                tooltip: {
                    bodyColor: '#e0e0e0',
                    backgroundColor: '#333'
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#e0e0e0'
                    },
                    grid: {
                        color: '#e0e0e0'
                    }
                },
                y: {
                    ticks: {
                        color: '#e0e0e0'
                    },
                    grid: {
                        color: '#e0e0e0'
                    }
                }
            },
            layout: {
                padding: {
                    top: 20,
                    left: 20,
                    right: 20,
                    bottom: 20
                }
            }
        }
    });

            let userNames = data.avg_order_price.map(u => u.username);
            let userAvgSpent = data.avg_order_price.map(u => u.avg_spent);
            new Chart(document.getElementById('avgOrderChart'), {
                type: 'bar',
                data: {
                    labels: userNames,
                    datasets: [{
                        label: 'Средняя сумма заказа',
                        data: userAvgSpent,
                        backgroundColor: '#ffb01c'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#e0e0e0'
                            }
                        },
                        tooltip: {
                            bodyColor: '#e0e0e0',
                            backgroundColor: '#333'
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#e0e0e0'
                            },
                            grid: {
                                color: '#e0e0e0'
                            }
                        },
                        y: {
                            ticks: {
                                color: '#e0e0e0'
                            },
                            grid: {
                                color: '#e0e0e0'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            left: 20,
                            right: 20,
                            bottom: 20
                        }
                    }
                }
            });

            let orderDates = data.orders_per_day.map(o => o.order_date);
            let orderCounts = data.orders_per_day.map(o => o.order_count);
            new Chart(document.getElementById('ordersPerDayChart'), {
                type: 'line',
                data: {
                    labels: orderDates,
                    datasets: [{
                        label: 'Количество заказов в день',
                        data: orderCounts,
                        backgroundColor: '#05ffb0',
                        borderColor: '#05ffb0',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#e0e0e0'
                            }
                        },
                        tooltip: {
                            bodyColor: '#e0e0e0',
                            backgroundColor: '#333'
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#e0e0e0'
                            },
                            grid: {
                                color: '#e0e0e0'
                            }
                        },
                        y: {
                            ticks: {
                                color: '#e0e0e0'
                            },
                            grid: {
                                color: '#e0e0e0'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            left: 20,
                            right: 20,
                            bottom: 20
                        }
                    }
                }
            });
        }






        async function loadStatistics() {
            let response = await fetch('api/statistics.php');
            let data = await response.json();
            let productTable = document.getElementById('product-statistics');
            let userTable = document.getElementById('user-statistics');

            productTable.innerHTML = "";
            if (!data.products.length) {
                productTable.innerHTML = "<tr><td colspan='4'>Товары не найдены</td></tr>";
            } else {
                data.products.forEach(product => {
                    let row = document.createElement('tr');
                    row.innerHTML = `<td>${product.name}</td><td>${product.quantity}</td>`;
                    productTable.appendChild(row);
                });
            }

            userTable.innerHTML = "";
            if (!data.users.length) {
                userTable.innerHTML = "<tr><td colspan='3'>Пользователи не найдены</td></tr>";
            } else {
                data.users.forEach(user => {
                    let row = document.createElement('tr');
                    row.innerHTML = `<td>${user.username}</td><td>${user.total_orders}</td><td>${user.total_spent} руб.</td>`;
                    userTable.appendChild(row);
                });
            }
        }

        async function loadProducts() {
            let response = await fetch('api/products.php');
            let products = await response.json();
            let table = document.getElementById('product-list');
            table.innerHTML = "";

            if (!products.length) {
                table.innerHTML = "<tr><td colspan='6'>Товаров нет</td></tr>";
                return;
            }

            products.forEach(product => {
                let safeName = product.name.replace(/'/g, "&#39;").replace(/"/g, "&quot;");
                let safeManufacturer = product.manufacturer.replace(/'/g, "&#39;").replace(/"/g, "&quot;");
                let safeProductType = product.product_type.replace(/'/g, "&#39;").replace(/"/g, "&quot;");
                let safeDescription = product.description.replace(/'/g, "&#39;").replace(/"/g, "&quot;");
                let safeImage = product.image.replace(/'/g, "&#39;").replace(/"/g, "&quot;");

                let row = document.createElement('tr');
                row.innerHTML = 
                    `<td width="50">${product.id}</td>
                    <td>${product.name}</td>
                    <td width="75">${product.manufacturer}</td>
                    <td>${product.product_type}</td>
                    <td>${product.price} руб.</td>
                    <td width="150"><img src="${product.image}" width="100"></td>
                    <td width="150">${product.stock} шт.</td>
                    <td>
                        <button onclick="editProduct(${product.id}, '${safeName}', '${safeManufacturer}', '${safeProductType}','${safeDescription}', ${product.price}, '${safeImage}', ${product.stock})">Изменить</button>
                        <button id="del-button" onclick="deleteProduct(${product.id})">X</button>
                    </td>`;
                table.appendChild(row);
            });
        }

        async function loadUsers() {
            let response = await fetch('api/users.php');
            let users = await response.json();
            
            let userTable = document.getElementById('user-list');
            let adminTable = document.getElementById('admin-list');
            
            userTable.innerHTML = "";
            adminTable.innerHTML = "";

            if (!users.length) {
                userTable.innerHTML = "<tr><td colspan='5'>Пользователей нет</td></tr>";
                adminTable.innerHTML = "<tr><td colspan='5'>Администраторов нет</td></tr>";
                return;
            }

            users.forEach(user => {
                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>
                        <button onclick="toggleAdmin(${user.id}, ${user.is_admin})">
                            ${user.is_admin == 1 ? 'Разжаловать' : 'Повысить'}
                        </button>
                        <button id="del-button" onclick="deleteUser(${user.id})">X</button>
                    </td>
                `;

                if (user.is_admin == 1) {
                    adminTable.appendChild(row); 
                } else {
                    userTable.appendChild(row);
                }
            });
        }


        async function deleteUser(userId) {
            if (!confirm("Удалить пользователя?")) return;

            let response = await fetch(`api/delete_user.php?id=${userId}`, { method: "DELETE" });
            let result = await response.json();

            if (result.success) {
                loadUsers();
            } else {
                alert("Ошибка: " + result.error);
            }
        }


        async function toggleAdmin(userId, currentStatus) {
            let newStatus = currentStatus == 1 ? 0 : 1;

            let response = await fetch('api/update_user_role.php', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ user_id: userId, is_admin: newStatus })
            });

            let result = await response.json();
            if (result.success) {
                loadUsers();
            } else {
                alert("Ошибка: " + result.error);
            }
        }

        async function deleteProduct(id) {
            if (!confirm("Удалить товар?")) return;

            let response = await fetch(`api/products.php?id=${id}`, { method: "DELETE" });
            let result = await response.json();

            if (result.success) {
                loadProducts();
            } else {
                alert("Ошибка: " + result.message);
            }
        }

        function openAddModal() {
            document.getElementById('add-product-name').value = '';
            document.getElementById('add-product-manufacturer').value = '';
            document.getElementById('add-product-type').value = '';
            document.getElementById('add-product-description').value = '';
            document.getElementById('add-product-price').value = '';
            document.getElementById('add-product-image').value = '';
            document.getElementById('add-product-stock').value = '';

            document.getElementById('add-modal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        async function saveEditProduct() {
        let id = document.getElementById('edit-product-id').value;
        let name = document.getElementById('edit-product-name').value;
        let manufacturer = document.getElementById('edit-product-manufacturer').value;
        let product_type = document.getElementById('edit-product-type').value;
        let description = document.getElementById('edit-product-description').value;
        let price = document.getElementById('edit-product-price').value.replace(/\s+/g, ''); 
        let image = document.getElementById('edit-product-image').value;
        let stock = document.getElementById('edit-product-stock').value;

        if (!name || !manufacturer || !product_type || !description || !price || !image || !stock) {
            alert("Заполните все поля!");
            return;
        }

        let response = await fetch(`api/edit_product.php?id=${id}`, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ name, manufacturer, product_type, description, price, image, stock })
        });

        let result = await response.json();
        if (result.success) {
            closeModal('edit-modal');
            loadProducts();
        } else {
            alert("Ошибка: " + result.error);
        }
    }


        function editProduct(id, name, manufacturer, product_type, description, price, image, stock) {
            document.getElementById('edit-product-id').value = id;
            document.getElementById('edit-product-name').value = name;
            document.getElementById('edit-product-manufacturer').value = manufacturer;
            document.getElementById('edit-product-type').value = product_type;
            document.getElementById('edit-product-description').value = description;
            document.getElementById('edit-product-price').value = price;
            document.getElementById('edit-product-image').value = image;
            document.getElementById('edit-product-stock').value = stock;

            document.getElementById('edit-modal').style.display = 'block';
        }
        async function addNewProduct() {
        let name = document.getElementById('add-product-name').value;
        let manufacturer = document.getElementById('add-product-manufacturer').value;
        let product_type = document.getElementById('add-product-type').value;
        let description = document.getElementById('add-product-description').value;
        let price = document.getElementById('add-product-price').value.replace(/\s+/g, ''); 
        let image = document.getElementById('add-product-image').value;
        let stock = document.getElementById('add-product-stock').value;

        if (!name || !manufacturer || !description || !price || !image || !stock) {
            alert("Заполните все поля!");
            return;
        }

        let response = await fetch('api/add_product.php', {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ name, manufacturer, product_type, description, price, image, stock })
        });

        let result = await response.json();
        if (result.success) {
            closeModal('add-modal');
            loadProducts();
        } else {
            alert("Ошибка: " + result.error);
        }
    }
        
    </script>
</head>
<body>
    <header>
        <div class="header-container">
            <a href="profile.php" id="profile"><?php echo $profile_text ?></a>
            <div id="label-con" class="container">
                <a id="shop-label" href="aboutus.php">"Все по 300"</a>
                <p>админ панель</p>
            </div>
        </div>
    </header>

    <div class="tabs">
    <button onclick="showTab('products')">Товары</button>
    <button onclick="showTab('users')">Пользователи</button>
    <button onclick="showTab('admins')">Администрация</button>
    <button onclick="showTab('statistics')">Статистика</button>
    <button onclick="showTab('charts')">Графики</button>
    </div>


    <div id="edit-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('edit-modal')">X</span>
        <h2>Редактировать товар</h2>
        <input type="hidden" id="edit-product-id">
        <label for="edit-product-name">Название:</label>
        <input type="text" id="edit-product-name">
        <label for="edit-product-manufacturer">Производитель:</label>
        <input type="text" id="edit-product-manufacturer">
        <label for="edit-product-type">Тип товара:</label>
        <input type="text" id="edit-product-type">
        <label for="edit-product-description">Описание:</label>
        <textarea id="edit-product-description"></textarea>
        <label for="edit-product-price">Цена:</label>
        <input type="text" id="edit-product-price">
        <label for="edit-product-image">Изображение:</label>
        <input type="text" id="edit-product-image">
        <label for="edit-product-stock">Количество:</label>
        <input type="number" id="edit-product-stock">
        <div id="modal-btn-con" class="horizontal-container">
            <button onclick="saveEditProduct()">Сохранить изменения</button>
            <button onclick="closeModal('edit-modal')">Закрыть</button>
        </div>
    </div>
</div>




<div id="add-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span id="close-btn" class="close" onclick="closeModal('add-modal')">X</span>
        <h2>Добавить товар</h2>
        <label for="add-product-name">Название:</label>
        <input type="text" id="add-product-name">
        <label for="add-product-manufacturer">Производитель:</label>
        <input type="text" id="add-product-manufacturer">
        <label for="add-product-type">Тип товара:</label>
        <input type="text" id="add-product-type">
        <label for="add-product-description">Описание:</label>
        <textarea id="add-product-description"></textarea>
        <label for="add-product-price">Цена:</label>
        <input type="text" id="add-product-price">
        <label for="add-product-image">Изображение:</label>
        <input type="text" id="add-product-image">
        <label for="add-product-stock">Количество:</label>
        <input type="number" id="add-product-stock">
        <div id="modal-btn-con" class="horizontal-container">
            <button onclick="addNewProduct()">Добавить товар</button>
            <button id="close-btn" onclick="closeModal('add-modal')">Закрыть</button>
        </div>
    </div>
</div>




    <div id="products" class="tab-content">
        <h2>Товары</h2>
        <button onclick="openAddModal()">Добавить товар</button>
        <table>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Производитель</th>
                <th>Тип товара</th>
                <th>Цена</th>
                <th>Изображение</th>
                <th>Количество</th>
                <th>Действия</th>
            </tr>
            <tbody id="product-list"></tbody>
        </table>
    </div>

    <div id="users" class="tab-content" style="display: none;">
    <h2>Пользователи</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Роль</th>
        </tr>
        <tbody id="user-list"></tbody>
    </table>
</div>

<div id="admins" class="tab-content" style="display: none;">
    <h2>Администрация</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Роль</th>
        </tr>
        <tbody id="admin-list"></tbody>
    </table>
</div>

    <div id="statistics" class="tab-content" style="display: none;">
        <h2>Статистика</h2>
        <h3>Товары</h3>
        <table>
            <tr>
                <th>Название товара</th>
                <th>Количество заказов</th>
            </tr>
            <tbody id="product-statistics"></tbody>
        </table>

        <h3>Пользователи</h3>
        <table>
            <tr>
                <th>Имя пользователя</th>
                <th>Количество заказов</th>
                <th>Общая сумма</th>
            </tr>
            <tbody id="user-statistics"></tbody>
        </table>
    </div>
    <div>

    <div id="charts" class="tab-content" style="display: none;">
    <h2>Графики статистики</h2>

    <div class="chart-container">
        <h3>Популярные товары (по количеству заказов)</h3>
        <canvas id="popularProductsChart"></canvas>
    </div>

    <div class="chart-container">
        <h3>Средняя сумма заказа у пользователей</h3>
        <canvas id="avgOrderChart"></canvas>
    </div>

    <div class="chart-container">
        <h3>Количество заказов по дням</h3>
        <canvas id="ordersPerDayChart"></canvas>
    </div>
</div>



    <script>
        function showTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.getElementById(tab).style.display = 'block';
        }
    </script>
</body>
</html>
