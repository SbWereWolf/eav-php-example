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
    interface ILinkageEntity
    {
        /** Удалить связь
         * @param array $foreignKey значение внешнего ключа
         * @return bool значения колонок
         */
        public function dropLinkage(array $foreignKey):bool;

        /** Добавить запись в БД на основе экземпляра
         * @param array $foreignKey значение внешнего ключа
         * @return bool успех выполнения
         */
        public function addLinkage(array $foreignKey):bool;
    }
}
