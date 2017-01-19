<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:11
 */
namespace Assay\Permission\Privilege {

    use Assay\BusinessLogic\BussinessProcess;
    use Assay\BusinessLogic\UserInreface;
    use Assay\Communication\Profile\Profile;
    use Assay\Core\Common;
    use Assay\Core\MutableEntity;
    use Assay\DataAccess\SqlReader;

    class Session extends MutableEntity implements ISession
    {

        /** @var string название таблицы */
        const TABLE_NAME = 'session';
        public $cookies;

        public $key;
        public $companyFilter;
        public $mode;
        public $paging;
        public $greetingsRole;
        public $tablename = self::TABLE_NAME;

        public $userId;

        public function __construct()
        {
            //$this->cookies = Common::EMPTY_OBJECT;
            session_start();
            $this->key = session_id();
            $this->companyFilter = ISession::EMPTY_VALUE;
            $this->mode = ISession::EMPTY_VALUE;
            $this->paging = ISession::EMPTY_VALUE;
            $this->greetingsRole = ISession::EMPTY_VALUE;

            $this->userId = ISession::EMPTY_VALUE;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool
        {
            $result = false;

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
                $sqlReader = new SqlReader();
                $id[SqlReader::QUERY_PLACEHOLDER] = ':ID';
                $id[SqlReader::QUERY_VALUE] = $this->id;
                $id[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
                $key_field[SqlReader::QUERY_PLACEHOLDER] = ':KEY';
                $key_field[SqlReader::QUERY_VALUE] = $this->key;
                $key_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
                $user_id[SqlReader::QUERY_PLACEHOLDER] = ':USER_ID';
                $user_id[SqlReader::QUERY_VALUE] = $this->userId;
                $user_id[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
                $is_hidden[SqlReader::QUERY_PLACEHOLDER] = ':IS_HIDDEN';
                $is_hidden[SqlReader::QUERY_VALUE] = $this->isHidden;
                $is_hidden[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_INT;
                $arguments[SqlReader::QUERY_TEXT] = "
                    UPDATE 
                        ".$this->tablename." 
                    SET 
                        ".self::KEY."=".$key_field[SqlReader::QUERY_PLACEHOLDER].", ".self::USER_ID."=".$user_id[SqlReader::QUERY_PLACEHOLDER].",
                        ".self::IS_HIDDEN."=".$is_hidden[SqlReader::QUERY_PLACEHOLDER].",".self::ACTIVITY_DATE."=now()
                    WHERE 
                        ".self::ID."=".$id[SqlReader::QUERY_PLACEHOLDER]."
                ";
                $arguments[SqlReader::QUERY_PARAMETER] = [$id,$key_field,$user_id,$is_hidden];
                $result_sql = $sqlReader ->performQuery($arguments);
                $result = ($result_sql[SqlReader::ERROR_INFO][0] == "00000")?true:false;
            }

            return $result;

        }

        public static function open(string $userId):array
        {
            //$process = self::OPEN_PROCESS;
            //$object = self::SESSION_OBJECT;
            $result = array();
            $userId = ($userId == ISession::EMPTY_VALUE)?1:$userId; //Для теста. Если пустое значение, ставим ID гостя

            //$userRole = new UserRole($userId);
            $userProfile = new Profile();
            $userInterface = new UserInreface();
            $bussinessProcess = new BussinessProcess();
            //$isAllow = $userRole->userAuthorization($process, $object,$this->id);
            //if ($isAllow) {
            var_dump("Открываю сессию");
            session_start();
            $result[self::USER_ID] = $userId;
            var_dump("Генерирует новый ID сессии");
            $key = session_id();
            $result[self::KEY] = $key;

            $session = new Session();
            $id = $session->addEntity();
            $result[self::ID] = $id;
            $result[self::GREETINGS_ROLE] = $userProfile->getGreetingsRole();
            $result[self::MODE] = $bussinessProcess->getMode();
            $result[self::PAGING] = $userInterface->getPagging();
            $result[self::COMPANY_FILTER] = $userInterface->getCompanyFilter();

            $session->setByNamedValue($result);
            $session->mutateEntity();
            //}
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
            $this->greetingsRole = Common::setIfExists(
                self::GREETINGS_ROLE, $namedValues, ISession::EMPTY_VALUE
            );
            $this->isHidden = Common::setIfExists(
                self::IS_HIDDEN, $namedValues, self::DEFAULT_IS_HIDDEN
            );
            $this->userId = Common::setIfExists(
                self::USER_ID, $namedValues, ISession::EMPTY_VALUE
            );
            $this->id = Common::setIfExists(
                self::ID, $namedValues, ISession::EMPTY_VALUE
            );
        }

        public function getStored():array
        {
            $result = array();
            $sqlReader = new SqlReader();
            $id[SqlReader::QUERY_PLACEHOLDER] = ':ID';
            $id[SqlReader::QUERY_VALUE] = $this->id;
            $id[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_INT;
            $is_hidden[SqlReader::QUERY_PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden[SqlReader::QUERY_VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_INT;
            $arguments[SqlReader::QUERY_TEXT] = "
                    SELECT 
                        *
                    FROM 
                        ".$this->tablename."
                    WHERE 
                        ".self::IS_HIDDEN."=".$is_hidden[SqlReader::QUERY_PLACEHOLDER]." AND 
                        ".self::ID."=".$id[SqlReader::QUERY_PLACEHOLDER]."
                ";
            $arguments[SqlReader::QUERY_PARAMETER] = [$is_hidden,$id];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == Common::NO_ERROR) {
                $rows = $result_sql[SqlReader::RECORDS];
                $result = (count($rows) > 0)?$rows[0]:$result;
            }

            return $result;
        }

        public function loadByKey():array
        {
            $result = array();
            $sqlReader = new SqlReader();
            $key[SqlReader::QUERY_PLACEHOLDER] = ':KEY';
            $key[SqlReader::QUERY_VALUE] = $this->key;
            $key[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $is_hidden[SqlReader::QUERY_PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden[SqlReader::QUERY_VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_INT;
            $arguments[SqlReader::QUERY_TEXT] = "
                SELECT 
                    *
                FROM 
                    ".$this->tablename."
                WHERE 
                    ".self::IS_HIDDEN."=".$is_hidden[SqlReader::QUERY_PLACEHOLDER]." AND 
                    ".self::KEY."=".$key[SqlReader::QUERY_PLACEHOLDER]." AND ".self::IS_HIDDEN." = 0 
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [$is_hidden,$key];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == Common::NO_ERROR) {
                $rows = $result_sql[SqlReader::RECORDS];
                var_dump($rows);
                $result = (count($rows) > 0)?$rows[0]:$result;
            }
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
            $entity[self::GREETINGS_ROLE] = $this->greetingsRole;
            $entity[self::USER_ID] = $this->userId;

            return $entity;
        }

        public function setSession(): bool
        {
            var_dump($this);
            $result = true;
            $_SESSION[self::GREETINGS_ROLE] = $this->greetingsRole;
            $_SESSION[self::COMPANY_FILTER] = $this->companyFilter;
            $_SESSION[self::MODE] = $this->mode;
            $_SESSION[self::PAGING] = $this->paging;
            return $result;
        }

        public function setByCookie(Cookie $cookies)
        {
            $this->cookies = $cookies;

            $this->key = $this->cookies->key;
            $this->companyFilter = $this->cookies->companyFilter;
            $this->mode = $this->cookies->mode;
            $this->paging = $this->cookies->paging;
            $this->greetingsRole = $this->cookies->userName;
        }

        public function close():bool
        {
            var_dump("ЗАКРЫЛ СЕССИЮ");
            $this->isHidden = true;
            $result = $this->hideEntity();
            if ($result) {
                session_unset();
                session_destroy();
            }
            return $result;
        }
    }
}