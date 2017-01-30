<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:49
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\Core\ICommon;
    use Assay\InformationsCatalog\StructureInformation\Rubric;
    use Assay\InformationsCatalog\DataInformation;
    
    /**
     * Адрес компании
     */
    class CompanyAddress extends Rubric
    {
        /** @var string ссылка на позицию компании */
        const COMPANY = RubricPosition::EXTERNAL_ID;
        /** @var string ссылка на тип адреса */
        const TYPE = CompanyAddress::EXTERNAL_ID;
        
        /** @var string компания */
        public $company = ICommon::EMPTY_VALUE;
        /** @var string тип */
        public $type = AddressType::UNDEFINED;
    }
}
