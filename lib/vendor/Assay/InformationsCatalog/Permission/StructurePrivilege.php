<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:57
 */
namespace Assay\InformationsCatalog\Permission {

    use Assay\Core\Entity;

    class StructurePrivilege extends Entity
    {
        const EXTERNAL_ID = 'structure_privilege_id';

        const OPERATION = StructureProcess::EXTERNAL_ID;
        const OBJECT = StructureObject::EXTERNAL_ID;
        const INFORMATION_PRIVILEGE = InformationPrivilege::EXTERNAL_ID;
    }
}