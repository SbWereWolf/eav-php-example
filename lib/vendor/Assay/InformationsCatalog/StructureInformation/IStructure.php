<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:08
 */
namespace Assay\InformationsCatalog\StructureInformation {
    interface IStructure
    {
        const EXTERNAL_ID = 'structure_id';

        const PARENT = 'parent';

        public function addChild():int;

        public function getChildrenNames():array;

        public function getParent():int;

        public function isPartition():bool;

        public function isRubric():bool;

        public function getPath():array;

        public function getMap():array;

        public function search(string $searchString, string $structureCode):array;
    }
}
