document.addEventListener("DOMContentLoaded", function() {
    var userNameInput = document.getElementById("userName");
    var emailInput = document.getElementById("email");
    var firstNameInput = document.getElementById("firstName");
    var lastNameInput = document.getElementById("lastName");
    var patronymicInput = document.getElementById("patronymic");
    var saveBtn = document.getElementById("saveBtn");

    getUserData();

    function getUserData() {
        var userData = JSON.parse(localStorage.getItem("userData")) || {};

        userNameInput.value = userData.name || "";
        emailInput.value = userData.email || "";
        firstNameInput.value = userData.firstName || "";
        lastNameInput.value = userData.lastName || "";
        patronymicInput.value = userData.patronymic || "";
    }

    saveBtn.addEventListener("click", function() {
        var newName = userNameInput.value;
        var newEmail = emailInput.value;
        var newFirstName = firstNameInput.value;
        var newLastName = lastNameInput.value;
        var newPatronymic = patronymicInput.value;

        var userData = {
            name: newName,
            email: newEmail,
            firstName : newFirstName,
            lastName : newLastName,
            patronymic : newPatronymic
        };
        localStorage.setItem("userData", JSON.stringify(userData));
        alert("Данные успешно сохранены.");
    });
});