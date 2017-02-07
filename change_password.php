<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 26.01.17
 * Time: 18:24
 */

include "autoloader.php";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Смена пароля</title>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="assets/js/main.js"></script>
</head>
<body>
<form onsubmit="page.changePassword(); return false;" action="router.php" method="post">
    <label for="old_pass">Старый пароль</label>
    <input type="password" required id="old_pass">
    <label for="pass">Новый пароль</label>
    <input type="password" required id="pass">
    <label for="rep_pass">Повторить новый пароль</label>
    <input type="password" required id="rep_pass">
    <button type="submit">Сменить пароль</button>
</form>
</body>
</html>