<?php

include "autoloader.php";

function getRequestSession()
{
    $emptyData = \Assay\Core\ICommon::EMPTY_VALUE;
    $session = new Assay\Permission\Privilege\Session();
    if ($session->key != $emptyData) {
        $storedSession = $session->loadByKey();

        $session->key = Assay\Core\Common::setIfExists(Assay\Permission\Privilege\Session::KEY, $storedSession, $emptyData);
        $session->userId = Assay\Core\Common::setIfExists(Assay\Permission\Privilege\Session::USER_ID, $storedSession, $emptyData);
        $session->id = Assay\Core\Common::setIfExists(Assay\Permission\Privilege\Session::ID, $storedSession, $emptyData);
    }

    if ($session->key == $emptyData) {
        $sessionValues = Assay\Permission\Privilege\Session::open($session->userId);
        $session->setByNamedValue($sessionValues);
        $session->setSession();
    }
};

getRequestSession();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Глагне</title>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
<h3>
    Привет, <div id="greetings_role">никто</div>
</h3>
</body>
</html>
