<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:02
 */
namespace Assay\Core {
    /**
     * Реализация интерфейса обновления записи в БД
     */
    class MutableEntity extends Entity implements IMutableEntity, IReadableEntity
    {
        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool
        {
            $result = false;
            return $result;
        }
        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return array значения колонок
         */
        public function readEntity(string $id):array
        {
            $result = array();
            return $result;
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
        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {
            $result = array();
            return $result;
        }
        /** Добавить запись в БД на основе экземпляра
         * @return bool успех выполнения
         */
        public function addReadable():bool
        {
            $result = false;
            return $result;
        }

    }
}
