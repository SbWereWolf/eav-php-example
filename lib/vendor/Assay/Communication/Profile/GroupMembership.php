<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:39
 */
namespace Assay\Communication\Profile {

    use Assay\Core\Entity;

    class GroupMembership extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'group_membership_id';

        /** @var string колонка ссылки на социальную группу */
        const GROUP = SocialGroup::EXTERNAL_ID;
        /** @var string колонка ссылки на профиль пользователя */
        const PROFILE = PersonProfile::EXTERNAL_ID;
    }
}