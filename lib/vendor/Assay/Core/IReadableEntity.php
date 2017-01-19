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
         * @return bool значения колонок
         */
        public function readEntity(string $id):bool;

        /** Добавить запись в БД на основе экземпляра
         * @param array $namedValues значения колонок записи
         * @return bool успех выполнения
         */
        public function addReadable(array $namedValues):bool;
    }
}
