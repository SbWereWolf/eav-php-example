<?php

/**
 * @param $className string Class to load
 */

include "autoloader.php";

//include('index.php');

//include('index.php');
use Assay\Permission\Privilege;
use Assay\Core;


function getRequestSession():Assay\Permission\Privilege\Session
{
    $emptyData = Core\ICommon::EMPTY_VALUE;
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

    return $session;
}


function logOff(Assay\Permission\Privilege\Session $session):Assay\Permission\Privilege\Session
{
    $session->close();
    $defaultSession = new Assay\Permission\Privilege\Session();
    $sessionValues = Assay\Permission\Privilege\Session::open(Assay\Permission\Privilege\Account::EMPTY_VALUE);
    $session->setSession();
    $defaultSession->setByNamedValue($sessionValues);

    return $defaultSession;
}

function logOn(string $login, string $password):array
{
    $user = new Privilege\Account();
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
    return $result;
}


function registrationProcess(string $login, string $password, string $passwordConfirmation, string $email, string $object):bool{

    $result = false;

    $session = getRequestSession();

    $isAllow = authorizationProcess($session,Assay\Permission\Privilege\IProcessRequest::PROCESS_USER_REGISTRATION,$object);

    $registrationResult = false;
    if($isAllow){
        $user = new Assay\Permission\Privilege\Account();
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

    $isAllow = authorizationProcess($session,Assay\Permission\Privilege\IProcessRequest::PROCESS_CHANGE_PASSWORD, $object);
    $isCorrectPassword= false;
    if($isAllow){
        $isCorrectPassword = ($newPassword == $passwordConfirmation && $newPassword != $password);
    }

    $user = new Privilege\Account();
    $authenticationSuccess = false;
    if($isCorrectPassword){
        $user->id = $session->userId;

        $entityUser = $user->readEntity($user->id);

        $user->setByNamedValue($entityUser );
        $authenticationSuccess = $user->authentication($password);
    }

    if($authenticationSuccess){
        $result = $user->changePassword($newPassword);
    }

    return $result;
}


function passwordRecoveryProcess(string $email):bool{

    $result = false;

    $user = new Assay\Permission\Privilege\Account();
    $isSuccess = $user->loadByEmail($email);

    if($isSuccess){
        $result = $user->sendRecovery();
    }

    return $result;
}


function authorizationProcess(Assay\Permission\Privilege\Session $session, string $process, string $object):bool{

    $sessId = $session->id;
    $userRole = new Assay\Permission\Privilege\AccountRole($sessId);
    $result = $userRole->userAuthorization($process, $object,$sessId);
    return $result;
}

function testGrantRole(string $user_id,string $user_role_id):bool {
    $result = false;
    $userRole = new Privilege\AccountRole($user_id);
    $result = $userRole->grantRole($user_role_id);
    return $result;
}

function testRevokeRole(string $user_id,string $user_role_id):bool {
    $result = false;
    $userRole = new Privilege\AccountRole($user_id);
    $result = $userRole->revokeRole($user_role_id);
    return $result;
}
/*session_start();
session_regenerate_id();
var_dump(session_id());
session_unset();
session_destroy();
var_dump(session_id());*/

$session = getRequestSession();
//print phpinfo();

/*$logonResult = [];

$isAllow = authorizationProcess($session,'user_login','account');
var_dump("isAllow",$isAllow);

if($isAllow){
    $logonResult = logOn('sancho', 'qwerty');
}


$authenticationSuccess = Assay\Core\Common::setIfExists(0, $logonResult, false);
if ($authenticationSuccess) {
    $emptySession = new Assay\Permission\Privilege\Session();
    $session = Assay\Core\Common::setIfExists(1, $logonResult, $emptySession);
    $isAllow = authorizationProcess($session,'user_logout','account');
    if($isAllow){
        logOff($session);
    }
}*/


//var_dump($_COOKIE);
/*$sqlReader = new Assay\DataAccess\SqlReader();

$login[Assay\DataAccess\SqlReader::QUERY_PLACEHOLDER] = ':LOGIN';
$login[Assay\DataAccess\SqlReader::QUERY_VALUE] = 'sancho';
$login[Assay\DataAccess\SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;

$email[Assay\DataAccess\SqlReader::QUERY_PLACEHOLDER] = ':EMAIL';
$email[Assay\DataAccess\SqlReader::QUERY_VALUE] = 'mail@sancho.pw';
$email[Assay\DataAccess\SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;

$arguments[Assay\DataAccess\SqlReader::QUERY_TEXT] = "SELECT * FROM account WHERE login=".$login[Assay\DataAccess\SqlReader::QUERY_PLACEHOLDER]." AND email=".$email[Assay\DataAccess\SqlReader::QUERY_PLACEHOLDER];
$arguments[Assay\DataAccess\SqlReader::QUERY_PARAMETER] = [$login,$email];
$result = $sqlReader ->performQuery($arguments);
var_dump($result);
if ($result[Assay\DataAccess\SqlReader::ERROR_INFO][0] == '00000') {
    print $result[Assay\DataAccess\SqlReader::RECORDS][0]['email'];
}*/

//var_dump(registrationProcess('sancho','qwerty','qwerty','mail@sancho.pw','account'));
//var_dump(testRevokeRole(19,2));
//var_dump(testGrantRole(19,3));


var_dump(passwordChangeProcess('qwerty','123456','123456',''));
//passwordRecoveryProcess('mail@sancho.pw');
//$isAllow = authorizationProcess($session,'','');