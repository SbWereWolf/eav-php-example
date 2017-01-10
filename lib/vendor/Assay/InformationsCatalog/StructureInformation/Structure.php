<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:09
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\NamedEntity;

    class Structure extends NamedEntity implements IStructure    
    {
        public $parent;


        public function addEntity():int
        {

        }

        public function hideEntity():bool
        {

        }

        public function mutateEntity():bool
        {

        }

        public function getEntity(int $id):array
        {

        }

        public function getElementDescription():array
        {

        }


        public function addChild():int
        {

        }

        public function getChildrenNames():array
        {

        }

        public function getParent():int
        {

        }

        public function isPartition():bool
        {

        }

        public function isRubric():bool
        {

        }

        public function getPath():array
        {

        }

        public function getMap():array
        {

        }

        public function search(string $searchString, string $structureCode):array
        {
        }
    }
}
