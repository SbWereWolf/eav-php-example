<?php

/**
 * @param $className string Class to load
 */
/*
function autoload($className)
{
    $path = __DIR__ . "/lib/vendor/";
    $path = str_replace('\/',DIRECTORY_SEPARATOR,$path);
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
*/

/*
spl_autoload_register('autoload');

define('CONFIGURATION_ROOT', realpath(__DIR__.DIRECTORY_SEPARATOR.'configuration'));
define('DB_READ_CONFIGURATION', CONFIGURATION_ROOT.DIRECTORY_SEPARATOR.'db_read.ini');
*/
//include('index.php');

//include('index.php');
//use Assay\Permission\Privilege;

include "autoloader.php";

//include('index.php');

//include('index.php');
//use Assay\Permission\Privilege;
use Assay\Core;
use Assay\Communication\Profile\Profile;
use Assay\Communication\Profile\Company;
use Assay\Communication\Profile\Messages;

function getProfileData()
{
  $profile = new Profile();
  $profile->id = 1; //для тестов
  $profile->getCurrentUserProfileData();
  $profile->getUserEmail();
  return $profile;
}

$var = getProfileData();
//var_dump($var);

function setProfileData()
{
    $profile = new Profile();
    $profile->id = 1; //для тестов
    $name = 'Вася Пупкин';
    $description = 'Охрененный просто!!!';
    $city = 'Ёбург';
    $country = 'Рассея!';
    $code = null;
    $profile->setCurrentUserProfileData($name, 0, $code, $description, $city, $country);
 //   $profile->getUserEmail();
    return $profile;
}

//setProfileData();

function getProfileCompany()
{
    $profile = new Profile();
    $profile->id = 1; //для тестов
    $profile->getProfileCompany();
    //   $profile->getUserEmail();
    return $profile;
}

//$var = getProfileCompany();
//var_dump($var);

function getCurrentCompanyProfile()
{
    $company = new Company();
    $company->id = 1;
    $company->getCurrentCompanyProfileData();
    return $company;

}

//$var = getCurrentCompanyProfile();
//var_dump($var);


function setCompanyData()
{
    $company = new Company();
    $values = [];
    $company->id = 2; //для тестов
    $values['id'] = 2;
   // $values['name'] = 'Крутизна-крутая';
    //$values['description'] = 'Все еще самая лучшая';
    $values['name'] = 'Еще круче';
    $values['description'] = 'Уже нет. Просто жесть.';
    $values['employersCount'] = 50;
    $company->setCurrentUserCompanyData($values);
    //   $profile->getUserEmail();
    return $company;
}

//setCompanyData();

function addCompanyData()
{
    $company = new Company();
    $values = [];
   // $company->id = 1; //для тестов
  //  $values['id'] = 1;
    $values['name'] = 'Еще круче';
    $values['description'] = 'Уже нет';
    $values['employers_count'] = 50;
    $company->addCompanyData($values);
    //   $profile->getUserEmail();
    print_r($company);
    return $company;
}

//addCompanyData();


function getMessages()
{
    $message = new Messages();
    $message->profileId = 1; //для тестов
    $message->getMessagesList();
    //   $profile->getUserEmail();
    return $message;
}

$var = getMessages();
var_dump($var);

/*
function getRequestSession():Assay\Permission\Privilege\Session
{
    $emptyData = Privilege\ISession::EMPTY_VALUE;
    $session = new Assay\Permission\Privilege\Session();
    var_dump($session);
    //$cookie = new Assay\Permission\Privilege\Cookie();
    //$session->setByCookie($cookie);
    var_dump("session->key",$session->key);
    if ($session->key != $emptyData) {
        $storedSession = $session->loadByKey();
        var_dump("storedSession",$storedSession);

        $session->key = Assay\Core\Common::setIfExists(Assay\Permission\Privilege\Session::KEY, $storedSession, $emptyData);
        $session->userId = Assay\Core\Common::setIfExists(Assay\Permission\Privilege\Session::USER_ID, $storedSession, $emptyData);
        $session->id = Assay\Core\Common::setIfExists(Assay\Permission\Privilege\Session::ID, $storedSession, $emptyData);
    }

    if ($session->key == $emptyData) {
        $sessionValues = Assay\Permission\Privilege\Session::open($session->userId);
        $session->setByNamedValue($sessionValues);
        $session->setSession();
    }
    var_dump("_SESSION",$_SESSION);

    return $session;
}


function logOff(Assay\Permission\Privilege\Session $session):Assay\Permission\Privilege\Session
{
    $session->close();
    $defaultSession = new Assay\Permission\Privilege\Session();
    $sessionValues = Assay\Permission\Privilege\Session::open(Assay\Permission\Privilege\User::EMPTY_VALUE);
    $session->setSession();
    $defaultSession->setByNamedValue($sessionValues);

    return $defaultSession;
}

function logOn(string $login, string $password):array
{
    $user = new Privilege\User();
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
        var_dump("sessionValues",$sessionValues);
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
        $isCorrectPassword = ($newPassword == $passwordConfirmation && $newPassword != $password);
    }

    $user = new Privilege\User();
    $authenticationSuccess = false;
    if($isCorrectPassword){
        $user->id = $session->userId;
        $user->id = 2;

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

    $user = new Assay\Permission\Privilege\User();
    $isSuccess = $user->loadByEmail($email);

    if($isSuccess){
        $result = $user->sendRecovery();
    }

    return $result;
}


function authorizationProcess(Assay\Permission\Privilege\Session $session, string $process, string $object):bool{

    $sessId = $session->id;
    $userRole = new Assay\Permission\Privilege\UserRole($sessId);
    $result = $userRole->userAuthorization($process, $object,$sessId);
    return $result;
}

function testGrantRole(string $user_id,string $user_role_id):bool {
    $result = false;
    $userRole = new Privilege\UserRole($user_id);
    $result = $userRole->grantRole($user_role_id);
    return $result;
}

function testRevokeRole(string $user_id,string $user_role_id):bool {
    $result = false;
    $userRole = new Privilege\UserRole($user_id);
    $result = $userRole->revokeRole($user_role_id);
    return $result;
}
*/
/*session_start();
session_regenerate_id();
var_dump(session_id());
session_unset();
session_destroy();
var_dump(session_id());*/
/*
$session = getRequestSession();
//print phpinfo();

$logonResult = [];

$isAllow = authorizationProcess($session,'user_login','account');

if($isAllow){
    logOn('sancho', 'qwerty');
}
*/

/*
$authenticationSuccess = Assay\Core\Common::setIfExists(0, $logonResult, false);
var_dump("authenticationSuccess",$authenticationSuccess);
if ($authenticationSuccess) {
    $emptySession = new Assay\Permission\Privilege\Session();
    $session = Assay\Core\Common::setIfExists(1, $logonResult, $emptySession);
    $isAllow = authorizationProcess($session,'user_logout','account');
    var_dump("logout isAllow",$isAllow);
    if($isAllow){
        logOff($session);
    }
}
*/

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
//var_dump(testGrantRole(2,2));
//var_dump(testRevokeRole(2,2));

//var_dump(passwordChangeProcess('1','2','2',''));
//passwordRecoveryProcess('mail@sancho.pw');
//$isAllow = authorizationProcess($session,'','');