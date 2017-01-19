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

        /** Добавить запись сущности в БД
         * @return string идентификатор добавленой записи
         */
        public function addEntity():string;
    }
}
