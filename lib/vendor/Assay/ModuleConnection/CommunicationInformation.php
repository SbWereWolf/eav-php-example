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
    class CommunicationInformation extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_information_id';

        /** @var string колонка ссылки на объект общения */
        const COMMUNICATION = CommunicationObject::EXTERNAL_ID;
        /** @var string колонка ссылки на объект информационного каталога */
        const INFORMATION = InformationObject::EXTERNAL_ID;

        /** @var string назнвание таблицы для хранения данных сущности */
        const TABLE_NAME = 'communication_information';

        /** @var string назнвание таблицы для хранения данных сущности */
        protected $tablename = self::TABLE_NAME;
    }
}
