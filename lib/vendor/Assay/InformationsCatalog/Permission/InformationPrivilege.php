<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:56
 */
namespace Assay\InformationsCatalog\Permission {

    use Assay\Core\Entity;
    use Assay\Permission\Privilege\RoleDetail;

    class InformationPrivilege extends Entity
    {
        const EXTERNAL_ID = 'Information_privilege_id';

        const ROLE_DETAIL = RoleDetail::EXTERNAL_ID;
    }
}