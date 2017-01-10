<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:00
 */
namespace Assay\Core {
    class Entity implements IEntity
    {

        const TABLE_NAME = 'entity_table';

        public $id;
        public $isHidden;
        public $insertDate;

        public function AddEntity():int
        {
            $result = 0;
            return $result;
        }

        public function HideEntity():bool
        {
        }
    }
}