<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:07
 */
namespace Assay\Permission\Privilege {
    interface IAuthorizeProcess
    {
        /** Авторизация пользователя для выполнения процесса
         * @param string $process процесс
         * @param string $object объект
         * @return bool успех авторизации
         */
        public function userAuthorization(string $process, string $object):bool;
    }
}