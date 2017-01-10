<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:01
 */
namespace Assay\Core {
    class ReadableEntity extends Entity implements IReadableEntity
    {

        public function ReadEntity(string $id):array
        {
        }

        public function GetStored():array
        {
            $result = array();
            return $result;
        }

        public function SetByNamedValue(array $namedValue)
        {
        }
    }
}