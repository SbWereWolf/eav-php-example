<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:09
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\NamedEntity;

    class RoleDetail extends NamedEntity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'role_detail_id';

        /** @var string колонка ссылки на объект привелегий  */
        const PRIVILEGE = ObjectPrivilege::EXTERNAL_ID;
        /** @var string колонка ссылки на бизнес роль */
        const ROLE = BusinessRole::EXTERNAL_ID;

        /** @var string привелегия */
        public $privilege;
        /** @var string роль */
        public $role;
    }
}