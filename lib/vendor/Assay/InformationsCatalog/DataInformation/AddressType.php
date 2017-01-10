<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:49
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\Core\NamedEntity;

    class AddressType extends NamedEntity
    {
        const EXTERNAL_ID = 'address_type_id';

        const Undefined = 0;
        const Office = 1;
        const Mine = 2;
        const Construction = 3;
        const Garage = 4;

        public $value = self::Undefined;
    }
}