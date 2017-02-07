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
        /** @var string имя таблицы для хранения сущности */
        const TABLE_NAME = 'company_address';

        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'company_address_id';

        /** @var string ссылка на позицию компании */
        const COMPANY = RubricPosition::EXTERNAL_ID;
        /** @var string ссылка на тип адреса */
        const TYPE = AddressType::EXTERNAL_ID;
        
        /** @var string компания */
        public $company = self::EMPTY_VALUE;
        /** @var string тип */
        public $type = self::EMPTY_VALUE;
    }
}
