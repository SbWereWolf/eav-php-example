<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 25.01.17
 * Time: 12:28
 */

include "autoloader.php";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="assets/js/main.js"></script>
</head>
<body>
<form onsubmit="page.registration(); return false;" action="router.php" method="post">
    <label for="login">Логин</label>
    <input type="text" required id="login">
    <label for="pass">Пароль</label>
    <input type="password" required id="pass">
    <label for="rep_pass">Повторить пароль</label>
    <input type="password" required id="rep_pass">
    <label for="email">E-mail</label>
    <input type="email" required id="email">
    <button type="submit">Регистрация</button>
</form>
</body>
</html>