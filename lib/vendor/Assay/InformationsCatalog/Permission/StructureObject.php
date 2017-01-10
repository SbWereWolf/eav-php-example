<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:56
 */
namespace Assay\InformationsCatalog\Permission {

    use Assay\Core\Entity;
    use Assay\InformationsCatalog\StructureInformation\IStructure;

    class StructureObject extends Entity
    {
        const EXTERNAL_ID = 'structure_object_id';

        const STRUCTURE = IStructure::EXTERNAL_ID;
    }
}