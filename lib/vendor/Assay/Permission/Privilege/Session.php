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
    use Assay\Core\Entity;
    use Assay\Core\INamedEntity;
    use Assay\Core\MutableEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    class Session extends Entity implements ISession
    {

        /** @var string название таблицы */
        const TABLE_NAME = 'session';
        /** @var string колонка дата обновления */
        const UPDATE_DATE = 'update_date';

        public $key;
        public $companyFilter;
        public $mode;
        public $paging;
        public $greetingsRole;
        public $tablename = self::TABLE_NAME;

        public $userId;

        public function __construct()
        {
            session_start();
            $this->key = session_id();
            $this->companyFilter = Common::EMPTY_VALUE;
            $this->mode = Common::EMPTY_VALUE;
            $this->paging = Common::EMPTY_VALUE;
            $this->greetingsRole = Common::EMPTY_VALUE;

            $this->userId = Common::EMPTY_VALUE;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool
        {
            $result = false;

            $entity = $this->toEntity();
            $this->getStored();
            $storedData = $this->toEntity();

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
                $id[ISqlHandler::PLACEHOLDER] = ':ID';
                $id[ISqlHandler::VALUE] = $entity[self::ID];
                $id[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
                $key_field[ISqlHandler::PLACEHOLDER] = ':KEY';
                $key_field[ISqlHandler::VALUE] = $entity[self::KEY];
                $key_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
                $user_id[ISqlHandler::PLACEHOLDER] = ':USER_ID';
                $user_id[ISqlHandler::VALUE] = $entity[self::USER_ID];
                $user_id[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
                $is_hidden[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
                $is_hidden[ISqlHandler::VALUE] = $entity[self::IS_HIDDEN];
                $is_hidden[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
                $arguments[ISqlHandler::QUERY_TEXT] = "
                    UPDATE 
                        ".$this->tablename." 
                    SET 
                        ".self::KEY."=".$key_field[ISqlHandler::PLACEHOLDER].", ".self::USER_ID."=".$user_id[ISqlHandler::PLACEHOLDER].",
                        ".self::IS_HIDDEN."=".$is_hidden[ISqlHandler::PLACEHOLDER].",".self::UPDATE_DATE."=now()
                    WHERE 
                        ".self::ID."=".$id[ISqlHandler::PLACEHOLDER]."
                ";
                $arguments[ISqlHandler::QUERY_PARAMETER] = [$id,$key_field,$user_id,$is_hidden];
                $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
                $response = $sqlWriter->performQuery($arguments);

                $result = SqlHandler::isNoError($response);
                return $result;
            }

            return $result;

        }

        public static function open(string $userId):array
        {
            $result = array();
            $userId = ($userId == Common::EMPTY_VALUE)?1:$userId; //Для теста. Если пустое значение, ставим ID гостя

            $userProfile = new Profile();
            $userInterface = new UserInreface();
            $bussinessProcess = new BussinessProcess();
            session_start();
            $result[self::USER_ID] = $userId;
            $key = session_id();
            $result[self::KEY] = $key;

            $session = new Session();
            $id = $session->addEntity();
            $result[self::ID] = $session->id;
            $result[self::GREETINGS_ROLE] = $userProfile->getGreetingsRole();
            $result[self::MODE] = $bussinessProcess->getMode();
            $result[self::PAGING] = $userInterface->getPagging();
            $result[self::COMPANY_FILTER] = $userInterface->getCompanyFilter();

            $session->setByNamedValue($result);
            $session->mutateEntity();
            return $result;
        }

        public function setByNamedValue(array $namedValues):bool
        {
            $result = true;
            $this->key = Common::setIfExists(
                self::KEY, $namedValues, Common::EMPTY_VALUE
            );
            $this->companyFilter = Common::setIfExists(
                self::COMPANY_FILTER, $namedValues, Common::EMPTY_VALUE
            );
            $this->mode = Common::setIfExists(
                INamedEntity::CODE, $namedValues, Common::EMPTY_VALUE
            );
            $this->paging = Common::setIfExists(
                self::PAGING, $namedValues, Common::EMPTY_VALUE
            );
            $this->greetingsRole = Common::setIfExists(
                self::GREETINGS_ROLE, $namedValues, Common::EMPTY_VALUE
            );
            $this->isHidden = Common::setIfExists(
                self::IS_HIDDEN, $namedValues, self::DEFAULT_IS_HIDDEN
            );
            $this->userId = Common::setIfExists(
                self::USER_ID, $namedValues, Common::EMPTY_VALUE
            );
            $this->id = Common::setIfExists(
                self::ID, $namedValues, Common::EMPTY_VALUE
            );
            return $result;
        }

        public function getStored():bool
        {
            $result = $this->readEntity($this->id);
            return $result;
        }

        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return array значения колонок
         */
        public function readEntity(string $id):bool
        {
            $id_field[ISqlHandler::PLACEHOLDER] = ':ID';
            $id_field[ISqlHandler::VALUE] = $id;
            $id_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            $is_hidden[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden[ISqlHandler::VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] ='
                SELECT 
                    *
                FROM 
                    '.$this->tablename.' as S,'.AccountRole::TABLE_NAME.' as AR,'.Account::TABLE_NAME.' as A,'.BusinessRole::TABLE_NAME.' AS R
                WHERE 
                    S.'.self::IS_HIDDEN.'='.$is_hidden[ISqlHandler::PLACEHOLDER].' AND 
                    S.'.self::ID.'='.$id_field[ISqlHandler::PLACEHOLDER].' AND 
                    S.'.self::USER_ID.'=A.'.self::ID.' AND 
                    A.'.self::ID.'=AR.'.Account::EXTERNAL_ID.' AND 
                    R.'.self::ID.'=AR.'.BusinessRole::EXTERNAL_ID.'
                    ';
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$is_hidden,$id_field];
            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            $result = $record != Common::EMPTY_ARRAY;

            return $result;
        }

        public function loadByKey():array
        {
            $result = array();
            $key[ISqlHandler::PLACEHOLDER] = ':KEY';
            $key[ISqlHandler::VALUE] = $this->key;
            $key[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            $is_hidden[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden[ISqlHandler::VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            $arguments[ISqlHandler::QUERY_TEXT] = "
                SELECT 
                    *
                FROM 
                    ".$this->tablename."
                WHERE 
                    ".self::IS_HIDDEN."=".$is_hidden[ISqlHandler::PLACEHOLDER]." AND 
                    ".self::KEY."=".$key[ISqlHandler::PLACEHOLDER]."
            ";
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$is_hidden,$key];
            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $isSuccessfulRead = SqlHandler::isNoError($response);

            if ($isSuccessfulRead) {
                $result = SqlHandler::getFirstRecord($response);
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
            $result = true;
            $_SESSION[self::GREETINGS_ROLE] = $this->greetingsRole;
            $_SESSION[self::COMPANY_FILTER] = $this->companyFilter;
            $_SESSION[self::MODE] = $this->mode;
            $_SESSION[self::PAGING] = $this->paging;
            return $result;
        }

        public function close():bool
        {
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