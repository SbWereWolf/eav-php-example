<?php

/**
 * @param $className string Class to load
 */
function autoload($className)
{
    $path = __DIR__ . "\\lib\\vendor\\";
    $path = str_replace('\\',DIRECTORY_SEPARATOR,$path);
    $className = ltrim($className, '\\');
    $fileName  = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    $classSource = ($path.$fileName);
    require ($classSource);
}

spl_autoload_register('autoload');

include('index.php');

include('index.php');
use Assay\Permission\Privilege;
use Assay\Core;


function getRequestSession():Assay\Permission\Privilege\Session
{
    $emptyData = Privilege\ISession::EMPTY_VALUE;


    $session = new Assay\Permission\Privilege\Session();
    $cookie = new Assay\Permission\Privilege\Cookie();
    $session->setByCookie($cookie);

    if ($session->key != $emptyData) {
        $storedSession = $session->getStored();


        $session->key = Assay\Core\Common::setIfExists(Assay\Permission\Privilege\Session::KEY, $storedSession, $emptyData);
        $session->userId = Assay\Core\Common::setIfExists(Assay\Permission\Privilege\Session::USER_ID, $storedSession, $emptyData);
    }

    if ($session->key == $emptyData) {
        $sessionValues = Assay\Permission\Privilege\Session::open($session->userId);
        $session->setByNamedValue($sessionValues);
    }

    return $session;
}


function logOff(Assay\Permission\Privilege\Session $session):Assay\Permission\Privilege\Session
{
    $session->close();
    $defaultSession = new Assay\Permission\Privilege\Session();
    $sessionValues = Assay\Permission\Privilege\Session::open(Assay\Permission\Privilege\User::EMPTY_VALUE);
    $defaultSession->setByNamedValue($sessionValues);

    return $defaultSession;
}

function logOn(string $login, string $password):array
{
    $user = new Privilege\User();
    $user->login = $login;
    $storedUser = $user->getStored();

    $user->setByNamedValue($storedUser);
    $authenticationSuccess = $user->authentication($password);
    $user->updateActivityDate();


    $session = new Assay\Permission\Privilege\Session();
    if ($authenticationSuccess) {

        $currentSession = getRequestSession();
        $currentSession-> close();

        $sessionValues = Assay\Permission\Privilege\Session::open($user->id);
        $session->setByNamedValue($sessionValues);
    }
    $result = array($authenticationSuccess, $session);
    return $result;
}


function registrationProcess(string $login, string $password, string $passwordConfirmation, string $email, string $object):bool{

    $result = false;

    $session = getRequestSession();

    $isAllow = authorizationProcess($session,Assay\Permission\Privilege\IProcessRequest::USER_REGISTRATION,$object);

    $registrationResult = false;
    if($isAllow){
        $user = new Assay\Permission\Privilege\User();
        $registrationResult = $user->registration($login,$password,$passwordConfirmation,$email);
    }

    if($registrationResult){
        $logonResult = logOn($login,$password);
        $result = Assay\Core\Common::setIfExists(0, $logonResult, false);
    }
    return $result;
}


function passwordChangeProcess(string $password, string $newPassword, string $passwordConfirmation, string $object):bool{

    $result = false;

    $session = getRequestSession();

    $isAllow = authorizationProcess($session,Assay\Permission\Privilege\IProcessRequest::CHANGE_PASSWORD, $object);

    $isCorrectPassword= false;
    if($isAllow){
        $isCorrectPassword = $newPassword == $passwordConfirmation;
    }

    $user = new Privilege\User();
    $authenticationSuccess = false;
    if($isCorrectPassword){
        $user->id = $session->userId;
        $entityUser = $user->readEntity($user->id);

        $user->setByNamedValue($entityUser );
        $authenticationSuccess = $user->authentication($password);
    }

    if($authenticationSuccess){
        $user->changePassword($newPassword);
    }

    return $result;
}


function passwordRecoveryProcess(string $email):bool{

    $result = false;

    $user = new Assay\Permission\Privilege\User();
    $isSuccess = $user->loadByEmail($email);

    if($isSuccess){
        $result = $user->sendRecovery();
    }

    return $result;
}


function authorizationProcess(Assay\Permission\Privilege\Session $session, string $process, string $object):bool{

    $userId = $session->userId;
    $userRole = new Assay\Permission\Privilege\UserRole($userId);
    $result = $userRole->userAuthorization($process, $object);
    return $result;
}

$session = getRequestSession();

$logonResult = logOn('', '');

$authenticationSuccess = Assay\Core\Common::setIfExists(0, $logonResult, false);
if ($authenticationSuccess) {
    $emptySession = new Assay\Permission\Privilege\Session();
    $session = Assay\Core\Common::setIfExists(1, $logonResult, $emptySession);
    logOff($session);
}

registrationProcess('','','','','');
passwordChangeProcess('','','','');
passwordRecoveryProcess('');
$isAllow = authorizationProcess($session,'','');
