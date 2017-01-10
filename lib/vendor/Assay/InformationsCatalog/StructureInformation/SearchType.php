<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:10
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\NamedEntity;

    class SearchType extends NamedEntity
    {
        const EXTERNAL_ID = 'search_type_id';

        const Undefined = 0;
        const Like = 1;
        const Between = 2;
        const Enumeration = 3;

        public $value = self::Undefined;
    }
}