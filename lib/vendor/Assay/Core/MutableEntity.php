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
        public function mutateEntity():bool
        {

        }

        public function readEntity(string $id):array
        {
            $result = array();
            return $result;
        }

        public function getStored():array
        {
            $result = array();
            return $result;
        }

        public function setByNamedValue(array $namedValue)
        {
        }

        public function toEntity():array
        {
            $result = array();
            return $result;
        }

    }
}
