<?php
namespace Assay\Core {
    /**
     * Интерфейс для методов общего назначения
     */
    interface ICommon
    {
        /** @var string константа значение не задано для значимых типов */
        const EMPTY_VALUE = ' ';
        /** @var null константа значение не задано для ссылочных типов */
        const EMPTY_OBJECT = null;
        /** @var array константа значение не задано для массивов */
        const EMPTY_ARRAY = array();
        /** @var array константа индекс не определён */
        const NO_INDEX = -1;


        //Данные подключения к БД
        /** @var string название базы данных */
        const DB_NAME = "assay_catalog";
        /** @var string хост подключения */
        const DB_HOST = "localhost";
        /** @var string логин */
        const DB_LOGIN = "assay_manager";
        /** @var string пароль */
        const DB_PASSWORD = "df1funi";

        /**
         * Используется для инициализации переданным значение, если переданное значение не задано, то выдаётся значение по умолчанию
         * @param mixed $valueIfIsset переданное значение
         * @param mixed $valueIfNotIsset значение по умолчанию
         * @return mixed
         */
        public static function isSetEx($valueIfIsset, $valueIfNotIsset);

        /**
         * Используется для инициализации элементом массива, если элемент не задан, то выдаётся значение по умолчанию
         * @param $key string|int индекс элемента
         * @param $array array массив
         * @param $valueIfNotIsset mixed значение по умолчанию
         * @return mixed
         */
        public static function setIfExists($key, &$array, $valueIfNotIsset);
    }
}
