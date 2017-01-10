<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:01
 */
namespace Assay\Core {
    /**
     * Реализация интерфейс чтения сущности из БД
     */
    class ReadableEntity extends Entity implements IReadableEntity
    {

        public function readEntity(string $id):array
        {
        }

        public function getStored():array
        {
            $result = array();
            return $result;
        }

        public function setByNamedValue(array $namedValue)
        {
        }
    }
}
