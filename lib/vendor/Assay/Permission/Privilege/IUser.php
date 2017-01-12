<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:06
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Common;

    interface IUser
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'user_id';

        /** @var string колонка "Имя учётной записи" */
        const LOGIN = 'login';
        /** @var string колонка хэш пароля */
        const PASSWORD_HASH = 'password_hash';
        /** @var string колонка дата последней активности */
        const ACTIVITY_DATE = 'activity_date';
        /** @var string колонка электронная почта */
        const EMAIL = 'email';

        /** @var string константа "значение не определено" */
        const EMPTY_VALUE = Common::EMPTY_VALUE;
        /** @var string алгоритм по умолчанию для расчёта хэша */
        const DEFAULT_ALGORITHM = PASSWORD_BCRYPT;

        /** Регистрация учётной записи
         * @param string $login имя учётной записи
         * @param string $password пароль
         * @param string $passwordConfirmation подтвержение пароля
         * @param string $email электронная почты
         * @return bool успех выполнения
         */
        public function registration(string $login, string $password, string $passwordConfirmation, string $email):bool;

        /** Сохранение нового пароля учётной записи
         * @param string $newPassword новый пароль
         * @return bool успех выполнения
         */
        public function changePassword(string $newPassword):bool;

        /** Сброс пароля
         * @return bool успех выполнения
         */
        public function recoveryPassword():bool;

        /** Обновить дату активности учётной записи
         * @return bool успех выполнения
         */
        public function updateActivityDate():bool;

        /** Получить запись из БД
         * @return array значения колонок
         */
        public function getStored():array;

        /** Установить по умолчанию
         * @return bool успех выполнения
         */
        public function setByDefault():bool;

        /** Загрузить учётную запись по электронной почте
         * @param string $email
         * @return bool успех выполнения
         */
        public function loadByEmail(string $email):bool;

        /** Отправить ссылку для сброса пароля
         * @return bool успех выполнения
         */
        public function sendRecovery():bool;
        public function addEntity():string;
    }
}