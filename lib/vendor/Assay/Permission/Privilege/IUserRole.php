<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:07
 */
namespace Assay\Permission\Privilege {
    interface IUserRole
    {

        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'account_role_id';

        /** @var string колонка ссылки на учётную запись пользователя */
        const USER = IUser::EXTERNAL_ID;
        /** @var string колонка ссылки на бизнес роль */
        const ROLE = BusinessRole::EXTERNAL_ID;

        /** @var string колонка "Имя учётной записи" */
        const LOGIN = 'login';
        /** @var string колонка хэш пароля */
        const PASSWORD_HASH = 'password_hash';
        /** @var string колонка электронная почта */
        const EMAIL = 'email';

        /** Назначить роль
         * @param string $role роль
         * @return bool успех выполнения
         */
        public function grantRole(string $role):bool;

        /** снять роль
         * @param string $role роль
         * @return bool успех выполнения
         */
        public function revokeRole(string $role):bool;

        public function userAuthorization(string $process, string $object, string $sid):bool;
    }
}