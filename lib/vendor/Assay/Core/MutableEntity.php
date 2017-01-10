<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:02
 */
namespace Assay\Core {
    class MutableEntity extends Entity implements IMutableEntity, IReadableEntity
    {
        public function MutateEntity():bool
        {

        }

        public function ReadEntity(string $id):array
        {
            $result = array();
            return $result;
        }

        public function GetStored():array
        {
            $result = array();
            return $result;
        }

        public function SetByNamedValue(array $namedValue)
        {
        }

        public function ToEntity():array
        {
            $result = array();
            return $result;
        }

    }
}