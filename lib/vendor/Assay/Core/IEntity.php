<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 17:58
 */
namespace Assay\Core {
    interface IEntity
    {

        const ID = 'id';
        const IS_HIDDEN = 'is_hidden';
        const INSERT_DATE = 'insert_date';

        const DEFAULT_IS_HIDDEN = false;

        public function AddEntity():int;

        public function HideEntity():bool;
    }
}