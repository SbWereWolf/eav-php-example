<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:06
 */
namespace Assay\Permission\Privilege {
    interface IAuthenticateUser
    {
        /** Расчитать хэш
         * @param string $password пароль
         * @return string хэш пароля
         */
        public static function calculateHash(string $password):string;

        /** Аутентифицировать пользователя
         * @param string $password пароль
         * @return bool успех аутентификации
         */
        public function authentication(string $password):bool;
    }
}