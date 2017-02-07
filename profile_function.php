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
//use Assay\Communication\Profile\PersonProfile;
use Assay\BusinessLogic\Communication;
use Assay\Communication\Profile\Company;
use Assay\Communication\Profile\Message;
use Assay\Communication\Profile\SocialGroup;
/*
function getProfileData()
{
  $profile = new Profile();
  $profile->id = 1; //для тестов
  $profile->getCurrentUserProfileData();
  $profile->getUserEmail();
  return $profile;
}
*/

//$var = getProfileData();
//var_dump($var);
/*
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
*/

if(isset($_POST["myprofile"])){
    $profile = new Communication();
  //  $profile = new PersonProfile(1);
    $values = $_POST;
    $profile->setCurrentUserProfileData($values, 1);
   // header("Location:/profile_function.php?myprofile=1");
}

//setProfileData();
/*
function getProfileCompany()
{
    $profile = new Profile(1);
   // $profile->id = 1; //для тестов
   // $profile->getProfileCompany();
    //   $profile->getUserEmail();
    return $profile;
}
*/
//$var = getProfileCompany();
//var_dump($var);
/*
function getCurrentCompanyProfile()
{
    $company = new Company();
    $company->id = 1;
    $company->getCurrentCompanyProfileData();
    return $company;

}
*/

//$var = getCurrentCompanyProfile();
//var_dump($var);

/*
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
*/

if(isset($_POST["mycompany"])){
    $company = new Company($_POST["id"], 1);
    $values = $_POST;
    $company->setCurrentUserCompanyData($values);
    // header("Location:/profile_function.php?myprofile=1");
}

if(isset($_POST["addmycompany"])){
    $company = new Company(0, 1);
    $values = $_POST;
    $company->addCompanyData($values);
    // header("Location:/profile_function.php?myprofile=1");
}


//setCompanyData();
/*
function addCompanyData()
{
    $company = new Company();
    $values = [];
   // $company->id = 1; //для тестов
  //  $values['id'] = 1;
    $values['name'] = 'Фирма моей мечты';
    $values['description'] = 'Мне так хочется';
    $values['employers_count'] = 50;
    $company->addCompanyData($values);
    //   $profile->getUserEmail();
     return $company;
}
*/

//addCompanyData();


function getMessages()
{
    $message = new Messages();
    $message->profileId = 1; //для тестов
    $message->authorId = 3; //для тестов
    //$message->getMessagesSelectAuthor();
    //if(!$message->getMessagesList()) die('nikuya');
    if(!$message->getMessagesSelectAuthor()) die('nikuya');
    //print_r($message);
    //   $profile->getUserEmail();
    return $message;
}

//$var = getMessages();
//var_dump($var);


function addMessage()
{
    $message = new Messages(1);
    $message->profileId = 1; //для тестов
    $values = [];
    // $company->id = 1; //для тестов
    //  $values['id'] = 1;
    $values['author'] = '3';
    $values['receiver'] = '1';
    $values['message_text'] = 'Что-то сюда пишем. Важное или не очень важное.';
    $values['date'] = date('Y-m-d H:i:s');
    $message->addMessage($values);
    //   $profile->getUserEmail();
    return $message;
}


if(isset($_POST["addmessage"])){
    $message = new Message(1);
    $values = $_POST;
    $message->addMessage($values);
    // header("Location:/profile_function.php?myprofile=1");
}

//addMessage();

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

$disabled = '';
//$profile = new PersonProfile(1);
$communication = new Communication();
$profile = $communication->getCurrentUserProfileData(1);
//$profile->id = 1; //предположим, что мы залогинились и получили наш айдишник
//$profile->getCurrentUserProfileData();
//$profile->getUserEmail();
//$profile->getProfileCompany();
//$profile->getMode();
$ownProfile = $communication->isOwnProfile(1);
$email = $communication->getUserEmail(1);
if(!$ownProfile) $disabled = 'disabled';
?>

<html>
<head>
    <title>Тестовая страница</title>
</head>
<body>
<table>
    <tr>
        <td colspan="5">Пользователь: <?php echo($profile->name); ?></td>
    </tr>
    <tr>
        <td>
            <a href="profile_function.php?messages=all">Сообщения</a>
        </td>
        <td>
            <a href="profile_function.php?favorite=1">Избранное</a>
        </td>
        <td>
            <a href="profile_function.php?myprofile=1">Профиль</a>
        </td>
        <td>
            <a href="profile_function.php?mod=1">Сменить режим</a>
        </td>
        <td>
            <a href="profile_function.php?quit=1">Выйти</a>
        </td>
    </tr>
</table>

<br/><br/>

<?php if(isset($_GET["myprofile"])){ ?>

<br/><br/>
<form name="profile_data" method="post">
    <input type="hidden" name="id" value="<?php echo($profile->id); ?>" />
    <input type="hidden" name="myprofile" value="1" />
<table>
    <tr>
        <td>
            Дата регистрации:
        </td>
        <td>
            <?php echo($profile->registrationDate); ?>
        </td>
    </tr>
    <tr>
        <td>
            Имя учетной записи:
        </td>
        <td>
            <input type="text" name="name" value="<?php echo($profile->name); ?>" <?php echo($disabled); ?>"/>
        </td>
    </tr>
    <tr>
        <td>
            Электронная почта:
        </td>
        <td>
            <?php echo($email); ?>
        </td>
    </tr>
    <tr>
        <td>
            Страна:
        </td>
        <td>
            <input type="text" name="country" value="<?php echo($profile->country); ?>" <?php echo($disabled); ?>"/>
        </td>
    </tr>
    <tr>
        <td>
            Город:
        </td>
        <td>
            <input type="text" name="city" value="<?php echo($profile->city); ?>" <?php echo($disabled); ?>"/>
        </td>
    </tr>
    <?php if($ownProfile){ ?>
    <tr>
        <td colspan="2"><input type="submit" name="sub_profile" value="Сохранить" /></td>
    </tr>
    <?php } ?>
</table>
</form>
<table>
    <tr>
        <td>
            Компания:
        </td>
<?php
if($company = $communication->getProfileCompany(1)){ ?>
        <td><a href="profile_function.php?company=<?php echo($company['id']);?>"><?php echo($company['name']);?></a></td>
        <?php if($ownProfile){ ?>
            <td><input type="button" name="delete_company" value="Очистить"/></td>
        <?php } ?>
<?php
}
else{
?>
        <td></td>
        <?php if($ownProfile){ ?>
            <td><input type="button" name="add_company" value="Добавить" onclick="window.location = 'profile_function.php?company=0'"/></td>
        <?php } ?>
<?php } ?>
    </tr>
</table>
<table>
    <tr>
        <td>
            Объявление:
        </td>
        <?php
        if($profile->getAd()){ ?>
            <td><a href="profile_function.php?advert=<?php echo($profile->advert['id']);?>"><?php echo($profile->advert['name']);?></a></td>
        <?php if($ownProfile){ ?>
            <td><input type="button" name="delete_company" value="Очистить"/></td>
        <?php } ?>
            <?php
        }
        else{
            ?>
            <td></td>
        <?php if($ownProfile){ ?>
            <td><input type="button" name="add_advert" id="add_advert" value="Добавить" /></td>
        <?php } ?>
        <?php } ?>
    </tr>
</table>

<?php } ?>


<?php if(isset($_GET["messages"])){
    $messages = new Message(1);
    ?>
    <?php if($_GET["messages"] == 'all'){
        //выводим список сообщений
        $messages->getCorrespondent();
    ?>

        <table>

            <?php foreach($messages->messageList as $value){ ?>
            <tr>
                <td>
                    <a href="profile_function.php?messages=<?php echo($value["author"]); ?>">
                        <?php echo($value['author_name']." от ".$value["date"]); ?>
                    </a>
                </td>
            </tr>
            <tr>
                 <td>
                     <?php echo($value["message_text"]); ?>
                 </td>
            </tr>
            <?php } ?>

        </table>

    <?php } ?>

<?php if(intval($_GET["messages"]) >0){
    //выводим список сообщений конкретного автора
    $messages->getByCorrespondent($_GET["messages"]);
    ?>
    <form name="message_data" method="post">
        <input type="hidden" name ="author" value="<?php echo($messages->profileId); ?>" />
        <input type="hidden" name ="receiver" value="<?php echo($messages->authorId); ?>" />
        <input type="hidden" name ="author_is_company" value="<?php echo($messages->authorIsCompany); ?>" />
        <input type="hidden" name ="receiver_is_company" value="<?php echo($messages->receiverIsCompany); ?>" />
        <input type="hidden" name ="addmessage" value="1" />
        <table>
            <tr>
                <td>
                    Написать сообщение
                </td>
            </tr>
            <tr>
                <td>
                    <textarea name="message_text"></textarea>
                </td>
            </tr>
             <tr>
                <td colspan="2"><input type="submit" name="add_message" value="Отправить" /></td>
            </tr>
        </table>
    </form>
        <table>

            <?php foreach($messages->messagesSelectAuthor as $value){ ?>
                <tr>
                    <td bgcolor="green">
                              <?php if(isset($value['author_name']) && !empty($value['author_name']))
                                 echo($value['author_name']." от ".$value["date"]);
                              else
                                  echo($value['receiver_name']." от ".$value["date"]);

                              ?>
                     </td>
                </tr>
                <tr>
                    <td>
                        <?php echo($value["message_text"]); ?>
                    </td>
                </tr>
            <?php } ?>

        </table>


<?php } ?>
<?php } ?>

<?php if(isset($_GET["company"])){
    $company = new Company(intval($_GET["company"]), $profile->id);
    $ownCompany = $company->isUserCompany();
    if(!$ownCompany) $disabled = 'disabled';
    ?>

    <br/><br/>
    <form name="company_data" method="post">
        <?php if($company->id != 0){ ?>
        <input type="hidden" name="id" value="<?php echo($company->id); ?>"/>
        <input type="hidden" name="mycompany" value="<?php echo($company->id); ?>"/>
        <?php }else{ ?>
            <input type="hidden" name="id" value="0"/>
            <input type="hidden" name="addmycompany" value="1"/>
        <?php } ?>
        <table>
            <tr>
                <td>
                    Название:
                </td>
                <td>
                    <input type="text" name="name" value="<?php echo($company->name); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Количество сотрудников:
                </td>
                <td>
                    <input type="text" name="employers_count" value="<?php echo($company->employersCount); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Сферы деятельности:
                </td>
                <td>
                    <input type="text" name="sphere" value="<?php echo($company->sphere); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Другой параметр отбора:
                </td>
                <td>
                    <input type="text" name="other_criteria" value="<?php echo($company->otherCriteria); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Поставщик:
                </td>
                <td>
                    <input type="checkbox" name="is_supplier" value="1" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Транспортная компания:
                </td>
                <td>
                    <input type="checkbox" name="is_transport" value="1" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Описание:
                </td>
                <td>
                    <input type="text" name="description" value="<?php echo($company->description); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Адрес:
                </td>
                <td>
                    <input type="text" name="address" value="<?php echo($company->address); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Сайт:
                </td>
                <td>
                    <input type="text" name="website" value="<?php echo($company->website); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Почта:
                </td>
                <td>
                    <input type="text" name="email" value="<?php echo($company->email); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>

            <tr>
                <td>
                    Телефон:
                </td>
                <td>
                    <input type="text" name="phone" value="<?php echo($company->phone); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Часы работы:
                </td>
                <td>
                    <input type="text" name="worktime" value="<?php echo($company->worktime); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    ИНН:
                </td>
                <td>
                    <input type="text" name="inn" value="<?php echo($company->inn); ?>" <?php echo($disabled); ?>"/>
                </td>
            </tr>

            <?php if($ownCompany){ ?>
            <tr>
                <td colspan="2"><input type="submit" name="sub_profile" value="Сохранить" /></td>
            </tr>
            <?php }elseif($ownProfile){ ?>
            <tr>
                <td colspan="2"><input type="submit" name="add_company" value="Добавить" /></td>
            </tr>
            <?php } ?>
        </table>
    </form>
<?php } ?>


<?php if(isset($_GET["setgroup"])){
    $socialGroupId = 1;
   // if($profile->setGroup($socialGroupId)) echo ('social group ok');

   // $socialGroup = new SocialGroup();
   // print_r($socialGroup->isMember($profile->id, $socialGroupId));

    if($profile->purgeGroup()) echo ('social group purge ok');

    ?>

<?php } ?>


</body>
</html>
