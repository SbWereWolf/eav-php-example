<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 12:06
 */
namespace Assay\Communication\InformationsCatalog {

    use Assay\Core\Entity;
    use Assay\InformationsCatalog\DataInformation\IRubricPosition;
    use Assay\InformationsCatalog\DataInformation\RubricPosition;

    /**
     * Объект воплощения рубрики
     */
    class RubricPositionObject extends Entity // +Structure +Rubric
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'instance_object_id';

        /** @var string колонка ссылки на воплощение рубрики */
        const INSTANCE = RubricPosition::EXTERNAL_ID;
        /** @var string колонка ссылки на объект каталога */
        const INFORMATION_OBJECT = InformationObject::EXTERNAL_ID;

        /** @var string название таблицы для хранения данных сущности */
        const TABLE_NAME = 'instance_object';

        /** @var string название таблицы для хранения данных сущности */
        protected $tablename = self::TABLE_NAME;
    }
}
