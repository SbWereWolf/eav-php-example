<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:12
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\NamedEntity;

    class TypeEdit extends NamedEntity
    {
        const EXTERNAL_ID = 'type_edit_id';

        const Undefined = 0;
        const System = 1;
        const User = 2;
        const Company = 3;

        public $value = self::Undefined;
    }
}