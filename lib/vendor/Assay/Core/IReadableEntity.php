<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:01
 */
namespace Assay\Core {
    interface IReadableEntity
    {

        public function ReadEntity(string $id):array;

        public function GetStored():array;

        public function SetByNamedValue(array $namedValue);
    }
}