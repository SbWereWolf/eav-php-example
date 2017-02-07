<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 11:57
 */
namespace Assay\Communication\Permission {

    use Assay\Communication\InformationsCatalog\CommunicationObject;
    use Assay\Communication\Profile\SocialGroup;
    use Assay\Core\Entity;
    /**
     * Объект социальной группы
     */
    class SocialGroupObject extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'social_group_object_id';

        /** @var string колонка внешнего ключа для ссылки на социальную группу */
        const SOCIAL_GROUP = SocialGroup::EXTERNAL_ID;
        /** @var string колонка внешнего ключа для ссылки на объект общения */
        const COMMUNICATION = CommunicationObject::EXTERNAL_ID;

        /** @var string назнвание таблицы для хранения данных сущности */
        const TABLE_NAME = 'social_group_object';

        /** @var string назнвание таблицы для хранения данных сущности */
        protected $tablename = self::TABLE_NAME;
    }
}
