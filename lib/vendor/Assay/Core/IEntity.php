<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 17:58
 */
namespace Assay\Core {
    /**
     * Базовый интерфейс сущности
     */
    interface IEntity
    {
        /** @var string колонка для айди */
        const ID = 'id';
        /** @var string колонка признака "является скрытым" */
        const IS_HIDDEN = 'is_hidden';
        /** @var string колонка дата добавления */
        const INSERT_DATE = 'insert_date';
        /** @var string колонка дата обновления */
        const ACTIVITY_DATE = 'activity_date';
        /** @var string значение по умолчанию для признака "является скрытым" */
        const DEFAULT_IS_HIDDEN = '0';

        /** Добавить запись сущности в БД
         * @return string идентификатор добавленой записи
         */
        public function addEntity():string;

        /** Скрыть сущность
         * @return bool успех операции
         */
        public function hideEntity():bool;
    }
}
