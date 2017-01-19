<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:01
 */
namespace Assay\Core {
    /**
     * Реализация интерфейс чтения сущности из БД
     */
    class ReadableEntity extends Entity implements IReadableEntity
    {
        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return array значения колонок
         */
        public function readEntity(string $id):array
        {
        }
        /** Прочитать данные экземпляра из БД
         * @return array колонки
         */
        public function getStored():array
        {
            $result = array();
            return $result;
        }
        /** Установить свойства экземпляра в соответствии с массивом
         * @param array $namedValue массив значений
         */
        public function setByNamedValue(array $namedValue)
        {
        }
        /** Добавить запись в БД на основе экземпляра
         * @return bool успех выполнения
         */
        public function addReadable():bool
        {
            $result = true;
            return $result;
        }
    }
}
