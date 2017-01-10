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
        public $code;
        public $name;
        public $description;

        public function LoadByCode(string $code):array
        {
        }

        public function GetElementDescription():array
        {
        }
    }
}