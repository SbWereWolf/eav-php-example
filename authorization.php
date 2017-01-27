
<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 25.01.17
 * Time: 12:33
 */

include "autoloader.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
<form onsubmit="page.logOn(); return false;" action="router.php" method="post">
    <label for="login">Логин</label>
    <input type="text" id="login">
    <label for="pass">Пароль</label>
    <input type="password" id="pass">
    <button type="submit">Вход</button>
    <a href='recoveryPassword.php'>Восстановить пароль</a>
</form>
</body>
</html>