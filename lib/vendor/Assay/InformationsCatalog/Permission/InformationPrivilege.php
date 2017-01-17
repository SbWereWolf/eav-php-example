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
    /** Информационное разрешение */
    class InformationPrivilege extends Entity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'Information_privilege_id';

        /** @var string колонка для ссылки на разрешение роли */
        const ROLE_DETAIL = RoleDetail::EXTERNAL_ID;
    }
}
