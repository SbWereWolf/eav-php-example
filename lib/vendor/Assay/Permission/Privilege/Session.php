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
    use Assay\Core\NamedEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    class Session extends Entity implements ISession,ICommon
    {

        /** @var string название таблицы */
        const TABLE_NAME = 'session';

        public $key = Common::EMPTY_VALUE;
        public $companyFilter = Common::EMPTY_VALUE;
        public $mode = Common::EMPTY_VALUE;
        public $paging = Common::EMPTY_VALUE;
        public $greetingsRole = Common::EMPTY_VALUE;
        public $profileId = Common::EMPTY_VALUE;
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
                $idField = SqlHandler::setBindParameter(':ID',$entity[self::ID],\PDO::PARAM_STR);
                $keyField = SqlHandler::setBindParameter(':KEY',$entity[self::KEY],\PDO::PARAM_STR);
                $userField = SqlHandler::setBindParameter(':USER_ID',$entity[self::USER_ID],\PDO::PARAM_STR);
                $isHiddenField = SqlHandler::setBindParameter(':IS_HIDDEN',$entity[self::IS_HIDDEN],\PDO::PARAM_INT);

                $arguments[ISqlHandler::QUERY_TEXT] = "
                    UPDATE 
                        ".$this->tablename." 
                    SET 
                        ".self::KEY."=".$keyField[ISqlHandler::PLACEHOLDER].",
                        ".self::USER_ID."=".$userField[ISqlHandler::PLACEHOLDER].",
                        ".self::IS_HIDDEN."=".$isHiddenField[ISqlHandler::PLACEHOLDER].",
                        ".self::UPDATE_DATE."=now()
                    WHERE 
                        ".self::ID."=".$idField[ISqlHandler::PLACEHOLDER]."
                    RETURNING
                        ".self::KEY.",
                        ".self::USER_ID.",
                        ".self::IS_HIDDEN."
                ";
                $arguments[ISqlHandler::QUERY_PARAMETER] = [
                    $idField,
                    $keyField,
                    $userField,
                    $isHiddenField
                ];
                $record = SqlHandler::writeOneRecord($arguments);

                if ($record != ISqlHandler::EMPTY_ARRAY) {
                    $result = $this->setByNamedValue($record);
                }
            }

            return $result;

        }

        public static function open(string $userId):array
        {
            $result = array();
            $userId = ($userId == Common::EMPTY_VALUE)?1:$userId; //Для теста. Если пустое значение, ставим ID гостя

            $userProfile = new Profile($userId);
            $userInterface = new UserInreface();
            session_start();
            $result[self::USER_ID] = $userId;
            $key = session_id();
            $result[self::KEY] = $key;

            $session = new Session();
            $session->addEntity();
            $result[self::ID] = $session->id;
            $greetingsRole = $userProfile->getGreetingsRole();
            $result[self::GREETINGS_ROLE] = $greetingsRole[NamedEntity::CODE];
            $result[self::MODE] = $userInterface->getMode();
            $result[self::PAGING] = $userInterface->getPaging();
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
            $result = false;

            $idField = SqlHandler::setBindParameter(':ID',$id,\PDO::PARAM_STR);
            $isHiddenField = SqlHandler::setBindParameter(':IS_HIDDEN',self::DEFAULT_IS_HIDDEN,\PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] ='
                SELECT 
                    S.*,R.'.NamedEntity::CODE.'
                FROM 
                    '.$this->tablename.' as S,'.AccountRole::TABLE_NAME.' as AR,'.Account::TABLE_NAME.' as A,'.BusinessRole::TABLE_NAME.' AS R
                WHERE 
                    S.'.self::IS_HIDDEN.'='.$isHiddenField[ISqlHandler::PLACEHOLDER].' AND 
                    S.'.self::ID.'='.$idField[ISqlHandler::PLACEHOLDER].' AND 
                    S.'.self::USER_ID.'=A.'.self::ID.' AND 
                    A.'.self::ID.'=AR.'.Account::EXTERNAL_ID.' AND 
                    R.'.self::ID.'=AR.'.BusinessRole::EXTERNAL_ID.'
                LIMIT 1
                    ';
            $arguments[ISqlHandler::QUERY_PARAMETER] = [
                $isHiddenField,
                $idField
            ];
            $record = SqlHandler::readOneRecord($arguments);

            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }

            return $result;
        }

        public function loadByKey():array
        {
            $result = array();

            $keyField = SqlHandler::setBindParameter(':KEY',$this->key,\PDO::PARAM_STR);
            $isHiddenField = SqlHandler::setBindParameter(':IS_HIDDEN',self::DEFAULT_IS_HIDDEN,\PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] = "
                SELECT 
                    *
                FROM 
                    ".$this->tablename."
                WHERE 
                    ".self::IS_HIDDEN."=".$isHiddenField[ISqlHandler::PLACEHOLDER]." AND 
                    ".self::KEY."=".$keyField[ISqlHandler::PLACEHOLDER]."
                LIMIT 1
            ";
            $arguments[ISqlHandler::QUERY_PARAMETER] = [
                $isHiddenField,
                $keyField
            ];
            $record = SqlHandler::readOneRecord($arguments);

            $result = $record;

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