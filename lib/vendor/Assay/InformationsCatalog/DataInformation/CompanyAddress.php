<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:49
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\InformationsCatalog\StructureInformation\Rubric;

    class CompanyAddress extends Rubric
    {
        public $company;
        public $type = AddressType::Undefined;

    }
}