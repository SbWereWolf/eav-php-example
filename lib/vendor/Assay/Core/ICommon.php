<?php
namespace Assay\Core {
    /**
     * Интерфейс для методов общего назначения
     */
    interface ICommon
    {
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
