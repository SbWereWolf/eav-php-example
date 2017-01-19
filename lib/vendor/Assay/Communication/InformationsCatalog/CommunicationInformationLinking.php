<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 12:07
 */
namespace Assay\Communication\InformationsCatalog {

    use Assay\Core\Entity;
    /**
     * Стыковка объекста общения с информационным объектом 
     */
    class CommunicationInformationLinking extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_information_object_id';

        /** @var string колонка ссылки на социальный объект общения */
        const COMMUNICATION = CommunicationSocialObject::EXTERNAL_ID;
        /** @var string колонка ссылки на объект информационного каталога */
        const INFORMATION = InformationsCatalogObject::EXTERNAL_ID;
    }
}
