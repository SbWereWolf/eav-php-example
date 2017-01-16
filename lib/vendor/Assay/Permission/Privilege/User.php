<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:08
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Common;
    use Assay\Core\IEntity;
    use Assay\Core\MutableEntity;
    use Assay\DataAccess\SqlReader;

    class User extends MutableEntity implements IUser, IAuthenticateUser
    {
        /** @var string имя таблицы */
        const TABLE_NAME = 'account';
        /** @var string колонка дата добавления */
        const INSERT_DATE = 'insert_date';

        /** @var string имя учётной записи */
        public $login;
        /** @var string хэш пароля */
        public $passwordHash;
        /** @var string дата последней активности */
        public $activityDate;
        /** @var string электронная почта */
        public $email;

        /**
         * Используется для инициализации элементом массива, если элемент не задан, то выдаётся значение по умолчанию
         * @return string идентификатор добавленной записи БД
         */
        public function addEntity():string
        {
            $result = 0;
            $sqlReader = new SqlReader();
            $arguments[SqlReader::QUERY_TEXT] = "
                INSERT INTO 
                    ".self::TABLE_NAME." 
                    (
                      ".self::INSERT_DATE."
                    ) 
                VALUES 
                    (
                        now()
                    )
                RETURNING ".self::ID.";
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == '00000') {
                $rows = $result_sql[SqlReader::RECORDS];
                $result = (count($rows) > 0)?$rows[0][$this::ID]:$result;
            }

            return $result;
        }

        public function registration(string $login, string $password, string $passwordConfirmation, string $email):bool
        {
            $result = false;

            $isCorrectPassword = $password == $passwordConfirmation;
            if ($isCorrectPassword) {
                $this->activityDate = time();
                $this->email = $email;
                $this->isHidden = IEntity::DEFAULT_IS_HIDDEN;
                $this->login = $login;
                $this->passwordHash = self::calculateHash($password);

                $this->id = $this->addEntity();
                $result = $this->mutateEntity();
            }

            return $result;
        }

        public static function calculateHash(string $password, int $algorithm = self::DEFAULT_ALGORITHM):string
        {
            $result = password_hash($password, $algorithm);
            return $result;
        }

        public function getStored():array
        {
            $result = array();

            $sqlReader = new SqlReader();
            $id[SqlReader::QUERY_PLACEHOLDER] = ':ID';
            $id[SqlReader::QUERY_VALUE] = $this->id;
            $id[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $arguments[SqlReader::QUERY_TEXT] = "
                SELECT 
                   *
                FROM 
                  ".self::TABLE_NAME."
                WHERE 
                  ".self::ID."=".$id[SqlReader::QUERY_PLACEHOLDER]."
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [$id];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == '00000') {
                $rows = $result_sql[SqlReader::RECORDS];
                $result = (count($rows) > 0)?$rows[0]:$result;
            }

            return $result;
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
                $login[SqlReader::QUERY_PLACEHOLDER] = ':LOGIN';
                $login[SqlReader::QUERY_VALUE] = $this->login;
                $login[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
                $pass_hash[SqlReader::QUERY_PLACEHOLDER] = ':PASSWORD_HASH';
                $pass_hash[SqlReader::QUERY_VALUE] = $this->passwordHash;
                $pass_hash[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
                $email[SqlReader::QUERY_PLACEHOLDER] = ':EMAIL';
                $email[SqlReader::QUERY_VALUE] = $this->email;
                $email[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
                $arguments[SqlReader::QUERY_TEXT] = "
                    UPDATE 
                        ".self::TABLE_NAME."
                    SET 
                        ".self::LOGIN." = ".$login[SqlReader::QUERY_PLACEHOLDER].", ".self::PASSWORD_HASH." = ".$pass_hash[SqlReader::QUERY_PLACEHOLDER].", 
                        ".self::EMAIL." = ".$email[SqlReader::QUERY_PLACEHOLDER].",".self::ACTIVITY_DATE." = now()
                    WHERE 
                        ".self::ID." = ".$id[SqlReader::QUERY_PLACEHOLDER]."
                ";
                $arguments[SqlReader::QUERY_PARAMETER] = [$id,$login,$pass_hash,$email];
                $result_sql = $sqlReader ->performQuery($arguments);
                $result = ($result_sql[SqlReader::ERROR_INFO][0] == '00000')?true:false;
            }

            return $result;

        }
        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return array значения колонок
         */
        public function readEntity(string $id):array
        {
            $result = array();
            $this->id = $id;

            $sqlReader = new SqlReader();
            $id[SqlReader::QUERY_PLACEHOLDER] = ':ID';
            $id[SqlReader::QUERY_VALUE] = $this->id;
            $id[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $arguments[SqlReader::QUERY_TEXT] = "
                SELECT 
                   *
                FROM 
                  ".self::TABLE_NAME."
                WHERE 
                  ".self::ID."=".$id[SqlReader::QUERY_PLACEHOLDER]."
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [$id];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == '00000') {
                $rows = $result_sql[SqlReader::RECORDS];
                if (count($rows) > 0) {
                    $row = $rows[0];
                    $this->login = $row[self::LOGIN];
                    $this->passwordHash = $row[self::PASSWORD_HASH];
                    $this->activityDate = $row[self::ACTIVITY_DATE];
                    $this->email = $row[self::EMAIL];
                }
            }

            $result = $this->toEntity();
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
        public function setByNamedValue(array $namedValue)
        {
            $this->id = Common::setIfExists(self::ID, $namedValue, self::EMPTY_VALUE);
            $this->insertDate = Common::setIfExists(self::INSERT_DATE, $namedValue, self::EMPTY_VALUE);
            $this->isHidden = Common::setIfExists(self::IS_HIDDEN, $namedValue, self::EMPTY_VALUE);
            $this->login = Common::setIfExists(self::LOGIN, $namedValue, self::EMPTY_VALUE);
            $this->passwordHash = Common::setIfExists(self::PASSWORD_HASH, $namedValue, self::EMPTY_VALUE);
            $this->activityDate = Common::setIfExists(self::ACTIVITY_DATE, $namedValue, self::EMPTY_VALUE);
            $this->email = Common::setIfExists(self::EMAIL, $namedValue, self::EMPTY_VALUE);
        }

        public function loadByEmail(string $email):bool
        {
            $result = false;
            $sqlReader = new SqlReader();
            $email_field[SqlReader::QUERY_PLACEHOLDER] = ':EMAIL';
            $email_field[SqlReader::QUERY_VALUE] = $email;
            $email_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $arguments[SqlReader::QUERY_TEXT] = "
                SELECT 
                   *
                FROM 
                  ".self::TABLE_NAME."
                WHERE 
                  ".self::EMAIL."=".$email_field[SqlReader::QUERY_PLACEHOLDER]."
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [$email_field];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == '00000') {
                $rows = $result_sql[SqlReader::RECORDS];
                $result = (count($rows) > 0)?true:false;
            }
            return $result;
        }

        public function sendRecovery():bool
        {
            $result = true;
            return $result;
        }
    }
}