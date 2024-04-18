function register() {
    var username = document.getElementById("regUsername").value;
    var password = document.getElementById("regPassword").value;

    var users = JSON.parse(localStorage.getItem("users")) || {};

    users[username] = password;

    localStorage.setItem("users", JSON.stringify(users));

    alert("Пользователь успешно зарегистрирован.");
}

// Функция для аутентификации пользователя
function login() {
    var username = document.getElementById("loginUsername").value;
    var password = document.getElementById("loginPassword").value;

    var users = JSON.parse(localStorage.getItem("users")) || {};

    if (users[username] === password) {
        alert("Вход выполнен успешно.");
        window.location.href = "mainPage.html";
    } else {
        alert("Неверное имя пользователя или пароль.");
    }
}

// Назначаем обработчики событий
document.getElementById("registerBtn").addEventListener("click", register);
document.getElementById("loginBtn").addEventListener("click", login);