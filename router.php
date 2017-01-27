<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 24.01.17
 * Time: 15:34
 */

include "autoloader.php";

/*
function logOn(string $login, string $password,\Assay\Permission\Privilege\Session $old_session):array
{
    $result = [
        false,
        \Assay\Core\ICommon::EMPTY_VALUE
    ];
    $object = \Assay\Permission\Privilege\IProcessRequest::OBJECT_ACCOUNT;
    $action = \Assay\Permission\Privilege\IProcessRequest::PROCESS_USER_LOGON;
    $permission = new \Assay\Permission\InterfacePermission\Permission();
    $args = $permission->set($object,$action,$old_session->id);
    $resultPermission = $permission->checkPrivilege($args);
    $isAllow = $permission->getAllow($resultPermission);
    if ($isAllow) {
        $user = new \Assay\Permission\Privilege\Account();
        $user->login = $login;
        $storedUser = $user->loadByLogin();

        $user->setByNamedValue($storedUser);
        $authenticationSuccess = $user->authentication($password);
        $user->updateActivityDate();


        $session = new Assay\Permission\Privilege\Session();
        if ($authenticationSuccess) {

            $currentSession = getRequestSession();
            $currentSession->close();


            $sessionValues = $session->open($user->id);
            $session->userId = $sessionValues[$session::USER_ID];
            $session->key = $sessionValues[$session::KEY];
            $session->id = $sessionValues[$session::ID];
            $session->setByNamedValue($sessionValues);

            $session->setSession();
        }
        $result = array($authenticationSuccess, $session);
    }
    return $result;
}*/

/*
function authorizationProcess(Assay\Permission\Privilege\Session $session, string $process, string $object):bool{

    $sessId = $session->id;
    $userRole = new Assay\Permission\Privilege\AccountRole($sessId);
    $result = $userRole->userAuthorization($process, $object,$sessId);
    return $result;
}*/


/*
function registrationProcess(string $login, string $password, string $passwordConfirmation, string $email, string $object):bool{

    $result = false;
    $object = \Assay\Permission\Privilege\IProcessRequest::OBJECT_ACCOUNT;
    $action = \Assay\Permission\Privilege\IProcessRequest::PROCESS_USER_REGISTRATION;

    $session = getRequestSession();

    $isAllow = authorizationProcess($session,$action,$object);

    $registrationResult = false;
    $user = new Assay\Permission\Privilege\Account();
    if($isAllow){
        $registrationResult = $user->registration($login,$password,$passwordConfirmation,$email);
    }

    if($registrationResult){
        $accountRole = new \Assay\Permission\Privilege\AccountRole($user->id);
        $accountRole->grantRole("user");
        $logonResult = logOn($login,$password,$session);
        $result = Assay\Core\Common::setIfExists(0, $logonResult, false);
    }
    return $result;
}*/

/*
function logOff(Assay\Permission\Privilege\Session $session):Assay\Permission\Privilege\Session
{
    $object = \Assay\Permission\Privilege\IProcessRequest::OBJECT_ACCOUNT;
    $action = \Assay\Permission\Privilege\IProcessRequest::PROCESS_USER_LOGOUT;
    $permission = new \Assay\Permission\InterfacePermission\Permission();
    $args = $permission->set($object,$action,$session->id);
    $resultPermission = $permission->checkPrivilege($args);
    $isAllow = $permission->getAllow($resultPermission);
    if ($isAllow) {
        $session->close();
        $defaultSession = new Assay\Permission\Privilege\Session();
        $sessionValues = Assay\Permission\Privilege\Session::open(Assay\Permission\Privilege\Account::EMPTY_VALUE);
        $session->setSession();
        $defaultSession->setByNamedValue($sessionValues);
        $session = $defaultSession;
    }

    return $session;
}*/

/*
function passwordChangeProcess(string $password, string $newPassword, string $passwordConfirmation):bool{

    $result = false;

    $session = getRequestSession();

    $object = \Assay\Permission\Privilege\IProcessRequest::OBJECT_ACCOUNT;
    $action = \Assay\Permission\Privilege\IProcessRequest::PROCESS_CHANGE_PASSWORD;

    $isAllow = authorizationProcess($session,$action, $object);
    $isCorrectPassword= false;
    if($isAllow){
        $isCorrectPassword = ($newPassword == $passwordConfirmation && $newPassword != $password);
    }

    $user = new \Assay\Permission\Privilege\Account();
    $authenticationSuccess = false;
    if($isCorrectPassword){
        $user->id = $session->userId;

        $user->readEntity($user->id);

        $authenticationSuccess = $user->authentication($password);
    }

    if($authenticationSuccess){
        $result = $user->changePassword($newPassword);
    }

    return $result;
}*/

/*
function passwordRecoveryProcess(string $email,\Assay\Permission\Privilege\Session $session):bool{

    $result = false;

    $object = \Assay\Permission\Privilege\IProcessRequest::OBJECT_ACCOUNT;
    $action = \Assay\Permission\Privilege\IProcessRequest::PROCESS_PASSWORD_RESET;

    $permission = new \Assay\Permission\InterfacePermission\Permission();
    $args = $permission->set($object,$action,$session->id);
    $resultPermission = $permission->checkPrivilege($args);
    $isAllow = $permission->getAllow($resultPermission);
    if ($isAllow) {
        $user = new Assay\Permission\Privilege\Account();
        $isSuccess = $user->loadByEmail($email);

        if ($isSuccess) {
            $result = $user->sendRecovery();
        }
    }

    return $result;
}*/


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