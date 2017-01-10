<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.01.2017
 * Time: 17:14
 */

function autoload($className)
{
    $path = __DIR__."\\lib\\vendor\\";
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $path.$fileName;
}
spl_autoload_register('autoload');

include('index.php');


function GetRequestSession():Assay\Permission\Privilege\Session
{
    $emptyData = Assay\Permission\Privilege\ISession::EMPTY_VALUE;

    $session = new Assay\Permission\Privilege\Session();
    $cookie = new Assay\Permission\Privilege\Cookie();
    $session->SetByCookie($cookie);

    if ($session->key != $emptyData) {
        $storedSession = $session->GetStored();

        $session->key = Assay\Core\Common::SetIfExists(Assay\Permission\Privilege\Session::KEY, $storedSession, $emptyData);
        $session->userId = Assay\Core\Common::SetIfExists(Assay\Permission\Privilege\Session::USER_ID, $storedSession, $emptyData);
    }

    if ($session->key == $emptyData) {
        $sessionValues = Assay\Permission\Privilege\Session::Open($session->userId);
        $session->SetByNamedValue($sessionValues);
    }    

    return $session;
}

function LogOff(Assay\Permission\Privilege\Session $session):Assay\Permission\Privilege\Session
{
    $session->Close();
    $defaultSession = new Assay\Permission\Privilege\Session();
    $sessionValues = Assay\Permission\Privilege\Session::Open(Assay\Permission\Privilege\User::EMPTY_VALUE);
    $defaultSession->SetByNamedValue($sessionValues);

    return $defaultSession;
}

function LogOn(string $login, string $password):array
{
    $user = new Assay\Permission\Privilege\User();
    $user->login = $login;
    $storedUser = $user->GetStored();
    
    
    $user->SetByNamedValue($storedUser);
    $authenticationSuccess = $user->Authentication($password);
    $user->UpdateActivityDate();

    $session = new Assay\Permission\Privilege\Session();
    if ($authenticationSuccess) {

        $currentSession = GetRequestSession();
        $currentSession-> Close();

        $sessionValues = Assay\Permission\Privilege\Session::Open($user->id);
        $session->SetByNamedValue($sessionValues);
    }
    $result = array($authenticationSuccess, $session);
    return $result;
}

function RegistrationProcess(string $login,string $password,string $passwordConfirmation,string $email, string $object):bool{

    $result = false;

    $session = GetRequestSession();
    $isAllow = AuthorizationProcess($session,Assay\Permission\Privilege\IProcessRequest::USER_REGISTRATION,$object);

    $registrationResult = false;
    if($isAllow){
        $user = new Assay\Permission\Privilege\User();
        $registrationResult = $user->Registration($login,$password,$passwordConfirmation,$email);
    }

    if($registrationResult){
        $logonResult = LogOn($login,$password);
        $result = Assay\Core\Common::SetIfExists(0, $logonResult, false);
    }
    return $result;
}

function PasswordChangeProcess(string $password, string $newPassword, string $passwordConfirmation, string $object):bool{

    $result = false;

    $session = GetRequestSession();
    $isAllow = AuthorizationProcess($session,Assay\Permission\Privilege\IProcessRequest::CHANGE_PASSWORD, $object);

    $isCorrectPassword= false;
    if($isAllow){
        $isCorrectPassword = $newPassword == $passwordConfirmation;
    }

    $user = new Assay\Permission\Privilege\User();
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

    $user = new Assay\Permission\Privilege\User();
    $isSuccess = $user->LoadByEmail($email);
    
    if($isSuccess){
        $result = $user->SendRecovery();
    }
    
    return $result;
}

function AuthorizationProcess(Assay\Permission\Privilege\Session $session, string $process, string $object):bool{

    $userId = $session->userId;
    $userRole = new Assay\Permission\Privilege\UserRole($userId);
    $result = $userRole->UserAuthorization($process, $object);
    return $result;
}

$session = GetRequestSession();

$logonResult = LogOn('', '');
$authenticationSuccess = Assay\Core\Common::SetIfExists(0, $logonResult, false);
if ($authenticationSuccess) {
    $emptySession = new Assay\Permission\Privilege\Session();
    $session = Assay\Core\Common::SetIfExists(1, $logonResult, $emptySession);
    LogOff($session);
}

RegistrationProcess('','','','','');
PasswordChangeProcess('','','','');
PasswordRecoveryProcess('');
$isAllow = AuthorizationProcess($session,'','');
