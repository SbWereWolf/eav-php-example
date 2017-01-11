<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:01
 */
namespace Assay\Core {
    /**
     * Интерфейс чтения сущности из БД
     */
    interface IReadableEntity
    {
        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return array значения колонок
         */
        public function readEntity(string $id):array;


        /** Прочитать данные экземпляра из БД
         * @return array колонки
         */
        public function getStored():array;


        /** Установить свойства экземпляра в соответствии с массивом
         * @param array $namedValue массив значений
         */
        public function setByNamedValue(array $namedValue);

        /** Добавить запись в БД на основе экземпляра
         * @return bool успех выполнения
         */
        public function addReadable():bool;
    }
}
