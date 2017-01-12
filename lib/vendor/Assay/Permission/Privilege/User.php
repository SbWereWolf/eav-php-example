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

    class User extends MutableEntity implements IUser, IAuthenticateUser
    {
        /** @var string имя таблицы */
        const TABLE_NAME = 'account';
        /** @var string колонка дата добавления */
        const INSERT_DATE = 'insert_date';
        //Данные подключения к БД
        /** @var string название базы данных */
        const DB_NAME = "assay_catalog";
        /** @var string хост подключения */
        const DB_HOST = "localhost";
        /** @var string логин */
        const DB_LOGIN = "assay_manager";
        /** @var string пароль */
        const DB_PASSWORD = "df1funi";

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
            $dbh = new \PDO("pgsql:dbname=".$this::DB_NAME.";host=".$this::DB_HOST, $this::DB_LOGIN, $this::DB_PASSWORD);
            $sth = $dbh->prepare("
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
            ");
            $sth->execute();
            $rows = $sth->fetchAll(\PDO::FETCH_ASSOC);

            if (count($rows) > 0):
                $result = $rows[0][$this::ID];
            endif;

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

            $dbh = new \PDO("pgsql:dbname=".$this::DB_NAME.";host=".$this::DB_HOST, $this::DB_LOGIN, $this::DB_PASSWORD);
            $sth = $dbh->prepare("
                SELECT 
                   ".self::LOGIN.",".self::EMAIL."
                FROM 
                  ".self::TABLE_NAME."
                WHERE 
                  ".self::ID."=:ID
            ");
            $sth->bindValue(':ID', $this->id, \PDO::PARAM_STR);
            $sth->execute();
            $rows = $sth->fetchAll(\PDO::FETCH_ASSOC);

            if (count($rows) > 0):
                $result = $rows[0];
            endif;

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
            $dbh = new \PDO("pgsql:dbname=".$this::DB_NAME.";host=".$this::DB_HOST, $this::DB_LOGIN, $this::DB_PASSWORD);
            if ($needUpdate) {
                // UPDATE DB RECORD;
                $sth = $dbh->prepare("
                    UPDATE 
                        ".self::TABLE_NAME."
                    SET 
                        ".self::LOGIN." = :LOGIN, ".self::PASSWORD_HASH." = :PASSWORD_HASH, 
                        ".self::EMAIL." = :EMAIL,".self::ACTIVITY_DATE." = now()
                    WHERE 
                        ".self::ID." = :ID
                ");
                $sth->bindValue(':ID', $this->id, \PDO::PARAM_INT);
                $sth->bindValue(':LOGIN', $this->login, \PDO::PARAM_STR);
                $sth->bindValue(':EMAIL', $this->email, \PDO::PARAM_STR);
                $sth->bindValue(':PASSWORD_HASH', $this->passwordHash, \PDO::PARAM_STR);
                $sth->execute();
                $result = true;
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
            $result = true;
            return $result;
        }

        public function sendRecovery():bool
        {
            $result = true;
            return $result;
        }
    }
}