<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:48
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\Core\MutableEntity;
    use Assay\InformationsCatalog\StructureInformation\RubricProperty;
    /**
     * Значения свойства позиции рубрики
     */
    class InformationValue extends MutableEntity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'information_value_id';

        /** @var string колонка для ссылки на позицию рубрики */
        const INSTANCE = IInformationInstance::EXTERNAL_ID;
        /** @var string колонка для ссылки на рубрику */
        const PROPERTY = RubricProperty::EXTERNAL_ID;
        /** @var string значение свойства */
        const VALUE = 'value';

        /** @var string позиция рубрики */
        public $instanceId;
        /** @var string свойство */
        public $propertyId;
        /** @var string значение свойства */
        public $value;

    }
}
