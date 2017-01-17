<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:09
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Entity;

    class ObjectPrivilege extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'object_privilege_id';

        /** @var string колонка ссылки на бизнес объект */
        const BUSINESS_OBJECT = BusinessObject::EXTERNAL_ID;
        /** @var string колонка ссылки на бизнес процесс */
        const BUSINESS_PROCESS = BusinessProcess::EXTERNAL_ID;

        /** @var string бизнес процесс */
        public $businessProcess;
        /** @var string бизнес объект */
        public $businessObject;
    }
}