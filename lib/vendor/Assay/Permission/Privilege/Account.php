<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:08
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Common;
    use Assay\Core\Entity;
    use Assay\Core\IPrimitiveData;
    use Assay\Core\MutableEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    class Account extends Entity implements IAccount, IAuthenticateAccount, ICommon
    {
        /** @var string название таблицы */
        const TABLE_NAME = 'account';
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'account_id';

        /** @var string константа "значение не определено" */
        const EMPTY_VALUE = Common::EMPTY_VALUE;
        /** @var string имя учётной записи */
        public $login;
        /** @var string хэш пароля */
        public $passwordHash;
        /** @var string дата последней активности */
        public $activityDate;
        /** @var string электронная почта */
        public $email;
        protected $tablename = self::TABLE_NAME;

        public function registration(string $login, string $password, string $passwordConfirmation, string $email):bool
        {
            $result = false;
            $isCorrectPassword = $password == $passwordConfirmation;
            if ($isCorrectPassword) {
                $this->activityDate = time();
                $this->email = $email;
                $this->isHidden = self::DEFAULT_IS_HIDDEN;
                $this->login = $login;
                $this->passwordHash = self::calculateHash($password);

                $isNotExistAccount = $this->checkExistAccount();
                if ($isNotExistAccount) {
                    $this->addEntity();
                    $result = $this->mutateEntity();
                }
            }

            return $result;
        }

        public static function calculateHash(string $password, int $algorithm = PASSWORD_BCRYPT):string
        {
            $result = password_hash($password, $algorithm);
            return $result;
        }

        public function getStored():bool
        {
            $result = $this->readEntity($this->id);
            return $result;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool
        {
            $result = false;

            $stored = new Account();
            $wasReadStored = $stored->readEntity($this->id);

            $storedEntity = array();
            $entity = array();
            if ($wasReadStored) {
                $storedEntity = $stored->toEntity();
                $entity = $this->toEntity();
            }

            $isContain = Common::isOneArrayContainOther($entity, $storedEntity);
            if (!$isContain) {
                $result = $this->updateEntity();
            }
//print_r($result);
            return $result;
        }

        private function updateEntity():bool
        {
            $result = false;

            $idField = SqlHandler::setBindParameter(':ID',$this->id,\PDO::PARAM_INT);
            $loginField = SqlHandler::setBindParameter(':LOGIN',$this->login,\PDO::PARAM_STR);
            $passHashField = SqlHandler::setBindParameter(':PASSWORD_HASH',$this->passwordHash,\PDO::PARAM_STR);
            $emailField = SqlHandler::setBindParameter(':EMAIL',$this->email,\PDO::PARAM_STR);
            $isHiddenField = SqlHandler::setBindParameter(':IS_HIDDEN',self::DEFAULT_IS_HIDDEN,\PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] = '
                UPDATE 
                    '.$this->tablename.'
                SET 
                    '.self::LOGIN.' = '.$loginField[ISqlHandler::PLACEHOLDER].', 
                    '.self::PASSWORD_HASH.' = '.$passHashField[ISqlHandler::PLACEHOLDER].', 
                    '.self::EMAIL.' = '.$emailField[ISqlHandler::PLACEHOLDER].',
                    '.self::ACTIVITY_DATE.' = now()
                WHERE 
                    '.self::IS_HIDDEN.'='.$isHiddenField[ISqlHandler::PLACEHOLDER].' AND 
                    '.self::ID.' = '.$idField[ISqlHandler::PLACEHOLDER].'
                RETURNING
                    '.self::LOGIN.','.self::PASSWORD_HASH.','.self::EMAIL.'
            ';
            $arguments[ISqlHandler::QUERY_PARAMETER] = [
                $idField,
                $loginField,
                $passHashField,
                $emailField,
                $isHiddenField
            ];

            $record = SqlHandler::writeOneRecord($arguments);

            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }

            return $result;
        }

        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return array значения колонок
         */
        public function readEntity(string $id):bool
        {
            $result = false;
            $idField = SqlHandler::setBindParameter(':EMAIL',$id,\PDO::PARAM_INT);
            $isHiddenField = SqlHandler::setBindParameter(':IS_HIDDEN',self::DEFAULT_IS_HIDDEN,\PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] ='
                SELECT 
                    *
                FROM 
                    '.$this->tablename.'
                WHERE 
                    '.self::IS_HIDDEN.'='.$isHiddenField[ISqlHandler::PLACEHOLDER].' AND 
                    '.self::ID.'='.$idField[ISqlHandler::PLACEHOLDER].'
            ';
            $arguments[ISqlHandler::QUERY_PARAMETER] = [
                $isHiddenField,
                $idField
            ];

            $record = SqlHandler::readOneRecord($arguments);

            if ($record != Common::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }

            return $result;
        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {
            $entity = [];
            $entity[self::ID] = $this->id;
            $entity[self::IS_HIDDEN] = $this->isHidden;
            $entity[self::LOGIN] = $this->login;
            $entity[self::PASSWORD_HASH] = $this->passwordHash;
            $entity[self::ACTIVITY_DATE] = $this->activityDate;
            $entity[self::EMAIL] = $this->email;
            return $entity;
        }

        public function changePassword(string $newPassword):bool
        {
            $this->passwordHash = self::calculateHash($newPassword);
            $result = $this->mutateEntity();
            return $result;
        }

        public function recoveryPassword():bool
        {
            $result = true;
            // SEND EMAIL WITH RECOVERY LINK;
            return $result;
        }

        public function updateActivityDate():bool
        {
            $this->activityDate = time();
            $result = $this->mutateEntity();

            return $result;
        }

        public function authentication(string $password):bool
        {
            $result = password_verify($password, $this->passwordHash);

            return $result;
        }

        public function setByDefault():bool
        {
            // SET TO GUEST USER ;
        }

        /** Установить свойства объекта в соответствии с массивом
         * @param array $namedValue массив значений
         */
        public function setByNamedValue(array $namedValue):bool
        {
            $result = true;
            $this->id = Common::setIfExists(self::ID, $namedValue, self::EMPTY_VALUE);
            $this->isHidden = Common::setIfExists(self::IS_HIDDEN, $namedValue, self::EMPTY_VALUE);
            $this->login = Common::setIfExists(self::LOGIN, $namedValue, self::EMPTY_VALUE);
            $this->passwordHash = Common::setIfExists(self::PASSWORD_HASH, $namedValue, self::EMPTY_VALUE);
            $this->activityDate = Common::setIfExists(self::ACTIVITY_DATE, $namedValue, self::EMPTY_VALUE);
            $this->email = Common::setIfExists(self::EMAIL, $namedValue, self::EMPTY_VALUE);
            return $result;
        }

        public function loadByEmail(string $email):bool
        {
            $result = false;

            $email_field[ISqlHandler::PLACEHOLDER] = ':EMAIL';
            $email_field[ISqlHandler::VALUE] = $email;
            $email_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            $is_hidden[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden[ISqlHandler::VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            $arguments[ISqlHandler::QUERY_TEXT] = "
                SELECT 
                    NULL
                FROM 
                    ".$this->tablename."
                WHERE
                    ".self::IS_HIDDEN."=".$is_hidden[ISqlHandler::PLACEHOLDER]." AND 
                    ".self::EMAIL."=".$email_field[ISqlHandler::PLACEHOLDER]."
            ";
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$email_field,$is_hidden];
            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $isSuccessfulRead = SqlHandler::isNoError($response);
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $result = $record != Common::EMPTY_ARRAY;
            }
            return $result;
        }

        public function loadByLogin():array
        {
            $result = [];
            $login_field[ISqlHandler::PLACEHOLDER] = ':LOGIN';
            $login_field[ISqlHandler::VALUE] = $this->login;
            $login_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
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
                    ".self::LOGIN."=".$login_field[ISqlHandler::PLACEHOLDER]."
            ";
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$login_field,$is_hidden];
            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $isSuccessfulRead = SqlHandler::isNoError($response);
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                if ($record != Common::EMPTY_ARRAY) {
                    $this->setByNamedValue($record);
                    $result = $this->toEntity();
                }
            }
            return $result;
        }

        public function sendRecovery():bool
        {
            $result = true;
            return $result;
        }

        private function checkExistAccount():bool
        {
            $result = false;

            $login_field[ISqlHandler::PLACEHOLDER] = ':LOGIN';
            $login_field[ISqlHandler::VALUE] = $this->login;
            $login_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $email_field[ISqlHandler::PLACEHOLDER] = ':EMAIL';
            $email_field[ISqlHandler::VALUE] = $this->email;
            $email_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            $arguments[ISqlHandler::QUERY_TEXT] = '
                SELECT 
                    NULL
                FROM 
                    '.$this->tablename.'
                WHERE 
                    '.self::LOGIN.' = '.$login_field[ISqlHandler::PLACEHOLDER].' OR 
                    '.self::EMAIL.' = '.$email_field[ISqlHandler::PLACEHOLDER].'
            ';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $login_field;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $email_field;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $isSuccessfulRead = SqlHandler::isNoError($response);
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $result = $record == Common::EMPTY_ARRAY;
            }
            return $result;
        }
    }
}
