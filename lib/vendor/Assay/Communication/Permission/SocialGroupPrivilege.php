<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 11:58
 */
namespace Assay\Communication\Permission {

    use Assay\Core\Entity;
    /**
     * Объект социальной группы
     */
    class SocialGroupPrivilege extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'social_group_privilege_id';

        /** @var string колонка ссылки на социальный процесс */
        const PROCESS = CommunicationProcess::EXTERNAL_ID;
        /** @var string колонка ссылки на социальную группу */
        const OBJECT = SocialGroupObject::EXTERNAL_ID;
        /** @var string колонка ссылки на разрешение общения */
        const COMMUNICATION_PRIVILEGE = CommunicationPrivilege::EXTERNAL_ID;
    }
}
