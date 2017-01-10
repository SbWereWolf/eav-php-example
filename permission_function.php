<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.01.2017
 * Time: 17:14
 */

include('index.php');
use Assay\Permission\Privilege;
use Assay\Core;

function GetRequestSession():Privilege\Session
{
    $emptyData = Privilege\ISession::EMPTY_VALUE;

    $session = new Privilege\Session();
    $cookie = new Privilege\Cookie();
    $session->setByCookie($cookie);

    if ($session->key != $emptyData) {
        $storedSession = $session->getStored();

        $session->key = Core\Common::setIfExists(Privilege\Session::KEY, $storedSession, $emptyData);
        $session->userId = Core\Common::setIfExists(Privilege\Session::USER_ID, $storedSession, $emptyData);
    }

    if ($session->key == $emptyData) {
        $sessionValues = Privilege\Session::open($session->userId);
        $session->setByNamedValue($sessionValues);
    }    

    return $session;
}

function LogOff(Privilege\Session $session):Privilege\Session
{
    $session->close();
    $defaultSession = new Privilege\Session();
    $sessionValues = Privilege\Session::open(Privilege\User::EMPTY_VALUE);
    $defaultSession->setByNamedValue($sessionValues);

    return $defaultSession;
}

function LogOn(string $login, string $password):array
{
    $user = new Privilege\User();
    $user->login = $login;
    $storedUser = $user->getStored();


    $user->setByNamedValue($storedUser);
    $authenticationSuccess = $user->authentication($password);
    $user->updateActivityDate();

    $session = new Privilege\Session();
    if ($authenticationSuccess) {

        $currentSession = GetRequestSession();
        $currentSession->close();

        $sessionValues = Privilege\Session::open($user->id);
        $session->setByNamedValue($sessionValues);
    }
    $result = array($authenticationSuccess, $session);
    return $result;
}

function RegistrationProcess(string $login,string $password,string $passwordConfirmation,string $email, string $object):bool{

    $result = false;

    $session = GetRequestSession();
    $isAllow = AuthorizationProcess($session,Privilege\IProcessRequest::USER_REGISTRATION,$object);

    $registrationResult = false;
    if($isAllow){
        $user = new Privilege\User();
        $registrationResult = $user->registration($login, $password, $passwordConfirmation, $email);
    }

    if($registrationResult){
        $logonResult = LogOn($login,$password);
        $result = Core\Common::setIfExists(0, $logonResult, false);
    }
    return $result;
}

function PasswordChangeProcess(string $password, string $newPassword, string $passwordConfirmation, string $object):bool{

    $result = false;

    $session = GetRequestSession();
    $isAllow = AuthorizationProcess($session,Privilege\IProcessRequest::CHANGE_PASSWORD, $object);

    $isCorrectPassword= false;
    if($isAllow){
        $isCorrectPassword = $newPassword == $passwordConfirmation;
    }

    $user = new Privilege\User();
    $authenticationSuccess = false;
    if($isCorrectPassword){
        $user->id = $session->userId;
        $entityUser = $user->readEntity($user->id);
        $user->setByNamedValue($entityUser);
        $authenticationSuccess = $user->authentication($password);
    }

    if($authenticationSuccess){
        $user->changePassword($newPassword);
    }

    return $result;
}

function PasswordRecoveryProcess(string $email):bool{

    $result = false;

    $user = new Privilege\User();
    $isSuccess = $user->loadByEmail($email);
    
    if($isSuccess){
        $result = $user->sendRecovery();
    }
    
    return $result;
}

function AuthorizationProcess(Privilege\Session $session, string $process, string $object):bool{

    $userId = $session->userId;
    $userRole = new Privilege\UserRole($userId);
    $result = $userRole->userAuthorization($process, $object);
    return $result;
}

$session = GetRequestSession();

$logonResult = LogOn('', '');
$authenticationSuccess = Core\Common::setIfExists(0, $logonResult, false);
if ($authenticationSuccess) {
    $emptySession = new Privilege\Session();
    $session = Core\Common::setIfExists(1, $logonResult, $emptySession);
    LogOff($session);
}

RegistrationProcess('','','','','');
PasswordChangeProcess('','','','');
PasswordRecoveryProcess('');
$isAllow = AuthorizationProcess($session,'','');
