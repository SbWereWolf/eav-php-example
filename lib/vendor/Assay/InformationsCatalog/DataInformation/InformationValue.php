<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:48
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\Core\NamedEntity;
    use Assay\InformationsCatalog\StructureInformation\RubricProperty;

    class InformationValue extends NamedEntity
    {
        const EXTERNAL_ID = 'information_value_id';

        const INSTANCE = IInformationInstance::EXTERNAL_ID;
        const PROPERTY = RubricProperty::EXTERNAL_ID;
        const VALUE = 'value';

        public $instanceId;
        public $propertyId;
        public $value;

    }
}