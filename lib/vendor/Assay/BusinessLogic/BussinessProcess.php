<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 18.01.17
 * Time: 15:55
 */
namespace Assay\BusinessLogic {

    use Assay\Core\Common;
    use Assay\Core\ICommon;
    use Assay\Permission\InterfacePermission\Permission;
    use Assay\Permission\Privilege\Account;
    use Assay\Permission\Privilege\AccountRole;
    use Assay\Permission\Privilege\IProcessRequest;
    use Assay\Permission\Privilege\Session;

    class BussinessProcess implements IBussinessProcess
    {
        public function registrationProcess(array $args):bool
        {
            $result = false;

            $login = Common::setIfExists('login', $args, ICommon::EMPTY_VALUE);
            $password = Common::setIfExists('pass', $args, ICommon::EMPTY_VALUE);
            $passwordConfirmation = Common::setIfExists('rep_pass', $args, ICommon::EMPTY_VALUE);
            $email = Common::setIfExists('email', $args, ICommon::EMPTY_VALUE);

            $object = IProcessRequest::OBJECT_ACCOUNT;
            $action = IProcessRequest::PROCESS_USER_REGISTRATION;

            $session = getRequestSession();

            $isAllow = $this->authorizationProcess($session,$action,$object);

            $registrationResult = false;
            $user = new Account();
            if($isAllow){
                $registrationResult = $user->registration($login,$password,$passwordConfirmation,$email);
            }


            if($registrationResult){
                $accountRole = new AccountRole($user->id);
                $accountRole->grantRole(IProcessRequest::MODE_USER);
                /*$logonResult = $this->logOn($login,$password,$session);
                $result = Common::setIfExists(0, $logonResult, false);*/
                $result = $registrationResult;
            }


            return $result;
        }

        public function logOn(array $args,Session $old_session):array
        {
            $result = [
                false,
                ICommon::EMPTY_VALUE
            ];

            $login = Common::setIfExists('login', $args, ICommon::EMPTY_VALUE);
            $password = Common::setIfExists('pass', $args, ICommon::EMPTY_VALUE);

            $object = IProcessRequest::OBJECT_ACCOUNT;
            $action = IProcessRequest::PROCESS_USER_LOGON;
            $permission = new Permission();
            $args = $permission->set($object,$action,$old_session->id);
            $resultPermission = $permission->checkPrivilege($args);
            $isAllow = $permission->getAllow($resultPermission);
            if ($isAllow) {
                $user = new Account();
                $user->login = $login;
                $storedUser = $user->loadByLogin();

                $user->setByNamedValue($storedUser);
                $authenticationSuccess = $user->authentication($password);
                $user->updateActivityDate();


                $session = new Session();
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
        }

        public function logOff(Session $session):Session
        {
            $object = IProcessRequest::OBJECT_ACCOUNT;
            $action = IProcessRequest::PROCESS_USER_LOGOUT;
            $permission = new Permission();
            $args = $permission->set($object,$action,$session->id);
            $resultPermission = $permission->checkPrivilege($args);
            $isAllow = $permission->getAllow($resultPermission);
            if ($isAllow) {
                $session->close();
                $defaultSession = new Session();
                $sessionValues = Session::open(Account::EMPTY_VALUE);
                $session->setSession();
                $defaultSession->setByNamedValue($sessionValues);
                $session = $defaultSession;
            }

            return $session;
        }

        public function passwordChangeProcess(array $args):bool
        {

            $result = false;

            $password = Common::setIfExists('old_pass', $args, ICommon::EMPTY_VALUE);
            $newPassword = Common::setIfExists('pass', $args, ICommon::EMPTY_VALUE);
            $passwordConfirmation = Common::setIfExists('rep_pass', $args, ICommon::EMPTY_VALUE);

            $session = getRequestSession();

            $object = IProcessRequest::OBJECT_ACCOUNT;
            $action = IProcessRequest::PROCESS_CHANGE_PASSWORD;

            $isAllow = $this->authorizationProcess($session,$action, $object);
            $isCorrectPassword= false;
            if($isAllow){
                $isCorrectPassword = ($newPassword == $passwordConfirmation && $newPassword != $password);
            }

            $user = new Account();
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
        }

        public function passwordRecoveryProcess(array $args,Session $session):bool
        {

            $result = false;

            $email = Common::setIfExists('email', $args, ICommon::EMPTY_VALUE);

            $object = IProcessRequest::OBJECT_ACCOUNT;
            $action = IProcessRequest::PROCESS_PASSWORD_RESET;

            $permission = new Permission();
            $args = $permission->set($object,$action,$session->id);
            $resultPermission = $permission->checkPrivilege($args);
            $isAllow = $permission->getAllow($resultPermission);
            if ($isAllow) {
                $user = new Account();
                $isSuccess = $user->loadByEmail($email);

                if ($isSuccess) {
                    $result = $user->sendRecovery();
                }
            }

            return $result;
        }

        public function authorizationProcess(Session $session, string $process, string $object):bool
        {

            $sessId = $session->id;
            $userRole = new AccountRole($sessId);
            $result = $userRole->userAuthorization($process, $object,$sessId);
            return $result;
        }
    }
}