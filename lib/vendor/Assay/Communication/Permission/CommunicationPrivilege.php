<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 11:58
 */
namespace Assay\Communication\Permission {

    use Assay\Core\Entity;
    use Assay\Permission\Privilege\RoleDetail;

    class CommunicationPrivilege extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_privilege_id';

        /** @var string колонка ссылки на привелегию роли */
        const ROLE_DETAIL = RoleDetail::EXTERNAL_ID;
    }
}