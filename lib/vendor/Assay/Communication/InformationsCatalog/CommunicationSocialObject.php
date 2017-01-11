<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 12:06
 */
namespace Assay\Communication\InformationsCatalog {

    use Assay\Communication\Profile\SocialObject;
    use Assay\Core\Entity;

    class CommunicationSocialObject extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_social_object_id';

        /** @var string колонка ссылки на социальный объект */
        const SOCIAL_OBJECT = SocialObject::EXTERNAL_ID;
        /** @var string колонка ссылки на объект общения */
        const COMMUNICATION_OBJECT = CommunicationObject::EXTERNAL_ID;
    }
}