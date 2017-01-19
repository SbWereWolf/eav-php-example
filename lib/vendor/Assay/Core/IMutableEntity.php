<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:02
 */
namespace Assay\Core {
    /**
     * Интерфейс обновления записи в БД
     */
    interface IMutableEntity
    {
        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool;

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array;
        /** Прочитать данные экземпляра из БД
         * @return bool колонки
         */
        public function getStored():bool;


        /** Установить свойства экземпляра в соответствии с массивом
         * @param array $namedValue массив значений
         */
        public function setByNamedValue(array $namedValue);
    }
}
