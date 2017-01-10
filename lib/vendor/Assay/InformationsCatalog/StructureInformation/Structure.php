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


        public function AddEntity():int
        {

        }

        public function HideEntity():bool
        {

        }

        public function MutateEntity():bool
        {

        }

        public function GetEntity(int $id):array
        {

        }

        public function GetElementDescription():array
        {

        }


        public function AddChild():int
        {

        }

        public function GetChildrenNames():array
        {

        }

        public function GetParent():int
        {

        }

        public function IsPartition():bool
        {

        }

        public function IsRubric():bool
        {

        }

        public function GetPath():array
        {

        }

        public function GetMap():array
        {

        }

        public function Search(string $searchString, string $structureCode):array
        {
        }
    }
}