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
        /** Удалить связь по внутрейней ссылке
         * @param array $foreignKeys значение внешнего ключа
         * @return bool значения колонок
         */
        public function dropInnerLinkage(array $foreignKeys):bool;
        /** Удалить связь по внешней ссылке
         * @param array $foreignKeys значение внешнего ключа
         * @return bool значения колонок
         */
        public function dropOuterLinkage(array $foreignKeys):bool;

        /** Добавить запись в БД на основе экземпляра
         * @param array $foreignKeys значение внешнего ключа
         * @return bool успех выполнения
         */
        public function addLinkage(array $foreignKeys):bool;
    }
}
