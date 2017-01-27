<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 27.01.17
 * Time: 13:31
 */

include "autoloader.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Восстановление пароля</title>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
<form onsubmit="page.recoveryPassword(); return false;" action="router.php" method="post">
    <label for="email">E-mail</label>
    <input type="text" id="email">
    <button type="submit">Восстановить пароль</button>
</form>
</body>
</html>