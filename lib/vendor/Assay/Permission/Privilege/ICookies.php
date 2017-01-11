<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:07
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Common;

    interface ICookies
    {
        /** @var string номер сессии */
        const KEY = 'key';
        /** @var string фильтр по компании */
        const COMPANY_FILTER = 'company_filter';
        /** @var string режим */
        const MODE = 'mode';
        /** @var string пагинация */
        const PAGING = 'paging';
        /** @var string имя пользователя */
        const USER_NAME = 'user_name';

        /** @var string константа для пустого значения */
        const EMPTY_VALUE = Common::EMPTY_VALUE;

        /** Получить номер сесии
         * @return string номер сессии
         */
        public static function getKey():string;

        /** Получить фильтр по компании
         * @return string фильт по компании
         */
        public static function getCompanyFilter():string;

        /** Получить режим
         * @return string режим
         */
        public static function getMode():string;

        /** Получить пагинацию
         * @return string пагинация
         */
        public static function getPaging():string;

        /** Получить имя пользователя
         * @return string
         */
        public static function getUserName():string;

        /** Записать номер сессии
         * @return bool успех выполнения
         */
        public function setKey():bool;

        /** Записать фильтр по компании
         * @return bool успех выполнения
         */
        public function setCompanyFilter():bool;

        /** Записать режим
         * @return bool успех выполнения
         */
        public function setMode():bool;

        /** Записать пагинацию
         * @return bool успех выполнения
         */
        public function setPaging():bool;

        /** Записать имя пользователя
         * @return bool успех выполнения
         */
        public function setUserName():bool;

    }
}