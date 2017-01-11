<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:11
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Common;
    use Assay\Core\MutableEntity;

    class Session extends MutableEntity implements ISession
    {

        public $cookies;

        public $key;
        public $companyFilter;
        public $mode;
        public $paging;
        public $userName;

        public $userId;

        public function __construct()
        {
            $this->cookies = Common::EMPTY_OBJECT;

            $this->key = ISession::EMPTY_VALUE;
            $this->companyFilter = ISession::EMPTY_VALUE;
            $this->mode = ISession::EMPTY_VALUE;
            $this->paging = ISession::EMPTY_VALUE;
            $this->userName = ISession::EMPTY_VALUE;

            $this->userId = ISession::EMPTY_VALUE;
        }

        public static function open(string $userId):array
        {
            $process = self::OPEN_PROCESS;
            $object = self::SESSION_OBJECT;
            $result = array();

            $userRole = new UserRole($userId);
            $isAllow = $userRole->userAuthorization($process, $object);
            if ($isAllow) {
                $result[self::USER_ID] = $userId;
                $key = uniqid('', true);
                $result[self::KEY] = $key;

                $session = new Session();
                $id = $session->addEntity();
                $result[self::ID] = $id;

                $session->setByNamedValue($result);
                $session->mutateEntity();
            }
            return $result;
        }

        public function setByNamedValue(array $namedValues)
        {
            $this->key = Common::setIfExists(
                self::KEY, $namedValues, ISession::EMPTY_VALUE
            );
            $this->companyFilter = Common::setIfExists(
                self::COMPANY_FILTER, $namedValues, ISession::EMPTY_VALUE
            );
            $this->mode = Common::setIfExists(
                self::MODE, $namedValues, ISession::EMPTY_VALUE
            );
            $this->paging = Common::setIfExists(
                self::PAGING, $namedValues, ISession::EMPTY_VALUE
            );
            $this->userName = Common::setIfExists(
                self::USER_NAME, $namedValues, ISession::EMPTY_VALUE
            );
            $this->userId = Common::setIfExists(
                self::USER_ID, $namedValues, ISession::EMPTY_VALUE
            );
        }

        public function mutateEntity():bool
        {
            $storedData = $this->getStored();
            $entity = $this->toEntity();

            $needUpdate = false;
            foreach ($entity as $key => $column) {
                $isExist = array_key_exists($key, $storedData);
                $equal = true;
                if ($isExist) {
                    $equal = $column == $storedData[$key];
                }
                if (!$equal) {
                    $needUpdate = true;
                }
            }
            if ($needUpdate) {
                // UPDATE DB RECORD;
            }

            $result = true;
            return $result;

        }

        public function getStored():array
        {
            $result = array();
            return $result;
        }

        public function toEntity():array
        {
            $entity = [];
            $entity[self::ID] = $this->id;
            $entity[self::IS_HIDDEN] = $this->isHidden;
            $entity[self::KEY] = $this->key;
            $entity[self::COMPANY_FILTER] = $this->companyFilter;
            $entity[self::MODE] = $this->mode;
            $entity[self::PAGING] = $this->paging;
            $entity[self::USER_NAME] = $this->userName;
            $entity[self::USER_ID] = $this->userId;

            return $entity;
        }

        public function setByCookie(Cookie $cookies)
        {
            $this->cookies = $cookies;

            $this->key = $this->cookies->key;
            $this->companyFilter = $this->cookies->companyFilter;
            $this->mode = $this->cookies->mode;
            $this->paging = $this->cookies->paging;
            $this->userName = $this->cookies->userName;
        }

        public function close():bool
        {
            $result = $this->hideEntity();
            return $result;
        }
    }
}