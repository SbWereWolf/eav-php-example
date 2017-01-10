<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:03
 */
namespace Assay\Core {
    class NamedEntity extends MutableEntity implements INamedEntity
    {
        /** @var string код */
        public $code;
        /** @var string имя */
        public $name;
        /** @var string описание */
        public $description;

        public function loadByCode(string $code):array
        {
        }

        public function getElementDescription():array
        {
        }
    }
}
