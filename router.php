<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 24.01.17
 * Time: 15:34
 */

include "autoloader.php";

$html_user_panel = [
    "logon" => "
    <a href='registration.php'>Регистрация</a>
    <a href='authorization.php'>Вход</a>
    ",
    "logout" => "
    <a href='change_password.php'>Сменить пароль</a>
    <input type='button' value='Выход' onclick='page.logout()'>
    "
];

$result = [
    "error" => [
        "isError" => true,
        "message" => ""
    ],
    "result" => [

    ]
];

if (isset($_POST)):
    switch ($_POST['action']):
        case "load_page":
            $userInterface = new \Assay\BusinessLogic\UserInreface();
            $greetings_role = $userInterface->getGreetingsRole();
            $mode = $userInterface->getMode();
            $paging = $userInterface->getPaging();
            $html = '';
            if ($mode == 'guest'):
                $html = $html_user_panel['logon'];
            else:
                $html = $html_user_panel['logout'];
            endif;
            $result['error']['isError'] = false;
            $result['result'] = [
                "greetings_role" => $greetings_role,
                "mode" => $mode,
                "paging" => $paging,
                "html_user_auth_panel" => $html
            ];
            break;
        case "registration":
            $businessProcess = new \Assay\BusinessLogic\BussinessProcess();
            $result_registration = $businessProcess->registrationProcess($_POST);
            $result['error']['isError'] = !$result_registration;
            if (!$result_registration):
                $result['error']['message'] = "Произошла ошибка при регистрации";
            endif;
            break;
        case "logout":
            $businessProcess = new \Assay\BusinessLogic\BussinessProcess();
            $newSession = $businessProcess->logOff($session);
            $result_logout = $newSession == $session;
            $result['error']['isError'] = $result_logout;
            if ($result_logout):
                $result['error']['message'] = "Произошла ошибка при выходе";
            endif;
            break;
        case "logOn":
            $businessProcess = new \Assay\BusinessLogic\BussinessProcess();
            $resultLogon = $businessProcess->logOn($_POST,$session);
            $result['error']['isError'] = !$resultLogon[0];
            if (!$resultLogon[0]):
                $result['error']['message'] = "Произошла ошибка при входе";
            else:
                $session = $resultLogon[1];
            endif;
            break;
        case "change_password":
            $businessProcess = new \Assay\BusinessLogic\BussinessProcess();
            $result_change_password = $businessProcess->passwordChangeProcess($_POST);
            $result['error']['isError'] = !$result_change_password;
            if (!$result_change_password):
                $result['error']['message'] = "Произошла ошибка при регистрации";
            endif;
            break;
        case 'recoveryPassword':
            $businessProcess = new \Assay\BusinessLogic\BussinessProcess();
            $result_recovery = $businessProcess->passwordRecoveryProcess($_POST, $session);
            $result['error']['isError'] = !$result_recovery;
            if (!$result_recovery):
                $result['error']['message'] = "Произошла ошибка при восстановлении пароля";
            endif;
            break;
        default:
            $result['error']['message'] = "Видимо что-то случилось...";
    endswitch;
else:
    $result['error']['message'] = "Видимо что-то случилось...";
endif;

print json_encode($result);