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
        const TABLE_NAME = 'authentic_user';

        /** @var string имя учётной записи */
        public $login;
        /** @var string хэш пароля */
        public $passwordHash;
        /** @var string дата последней активности */
        public $activityDate;
        /** @var string электронная почта */
        public $email;

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

        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
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