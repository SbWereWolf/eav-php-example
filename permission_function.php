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
    $session->SetByCookie($cookie);

    if ($session->key != $emptyData) {
        $storedSession = $session->GetStored();

        $session->key = Core\Common::SetIfExists(Privilege\Session::KEY, $storedSession, $emptyData);
        $session->userId = Core\Common::SetIfExists(Privilege\Session::USER_ID, $storedSession, $emptyData);
    }

    if ($session->key == $emptyData) {
        $sessionValues = Privilege\Session::Open($session->userId);
        $session->SetByNamedValue($sessionValues);
    }    

    return $session;
}

function LogOff(Privilege\Session $session):Privilege\Session
{
    $session->Close();
    $defaultSession = new Privilege\Session();
    $sessionValues = Privilege\Session::Open(Privilege\User::EMPTY_VALUE);
    $defaultSession->SetByNamedValue($sessionValues);

    return $defaultSession;
}

function LogOn(string $login, string $password):array
{
    $user = new Privilege\User();
    $user->login = $login;
    $storedUser = $user->GetStored();
    
    
    $user->SetByNamedValue($storedUser);
    $authenticationSuccess = $user->Authentication($password);
    $user->UpdateActivityDate();

    $session = new Privilege\Session();
    if ($authenticationSuccess) {

        $currentSession = GetRequestSession();
        $currentSession-> Close();

        $sessionValues = Privilege\Session::Open($user->id);
        $session->SetByNamedValue($sessionValues);
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
        $registrationResult = $user->Registration($login,$password,$passwordConfirmation,$email);
    }

    if($registrationResult){
        $logonResult = LogOn($login,$password);
        $result = Core\Common::SetIfExists(0, $logonResult, false);
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
        $entityUser = $user->ReadEntity($user->id);
        $user->SetByNamedValue($entityUser );
        $authenticationSuccess = $user->Authentication($password);
    }

    if($authenticationSuccess){
        $user->ChangePassword($newPassword);
    }

    return $result;
}

function PasswordRecoveryProcess(string $email):bool{

    $result = false;

    $user = new Privilege\User();
    $isSuccess = $user->LoadByEmail($email);
    
    if($isSuccess){
        $result = $user->SendRecovery();
    }
    
    return $result;
}

function AuthorizationProcess(Privilege\Session $session, string $process, string $object):bool{

    $userId = $session->userId;
    $userRole = new Privilege\UserRole($userId);
    $result = $userRole->UserAuthorization($process, $object);
    return $result;
}

$session = GetRequestSession();

$logonResult = LogOn('', '');
$authenticationSuccess = Core\Common::SetIfExists(0, $logonResult, false);
if ($authenticationSuccess) {
    $emptySession = new Privilege\Session();
    $session = Core\Common::SetIfExists(1, $logonResult, $emptySession);
    LogOff($session);
}

RegistrationProcess('','','','','');
PasswordChangeProcess('','','','');
PasswordRecoveryProcess('');
$isAllow = AuthorizationProcess($session,'','');
