<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:00
 */
namespace Assay\Core {
    /**
     * реализация интерфейса для работы с именнуемыми сущностями
     */
    class Entity implements IEntity
    {
        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'entity_table';
        /** @var string идентификатор записи таблицы */
        public $id;
        /** @var string признак "является скрытым" */
        public $isHidden;
        /** @var string дата добавления записи */
        public $insertDate;

        public function addEntity():string
        {
            $result = 0;
            return $result;
        }

        public function hideEntity():bool
        {
        }
    }
}
