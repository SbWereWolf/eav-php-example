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

        public function AddChild():int;

        public function GetChildrenNames():array;

        public function GetParent():int;

        public function IsPartition():bool;

        public function IsRubric():bool;

        public function GetPath():array;

        public function GetMap():array;

        public function Search(string $searchString, string $structureCode):array;
    }
}