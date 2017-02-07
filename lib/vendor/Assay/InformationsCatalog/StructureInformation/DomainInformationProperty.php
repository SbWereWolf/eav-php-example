<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.01.2017
 * Time: 22:53
 */

namespace Assay\InformationsCatalog\StructureInformation;


use Assay\Core\InnerLinkageEntity;

class DomainInformationProperty extends InnerLinkageEntity
{

    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'information_property_information_domain_id';

    /** @var string имя таблицы БД для хранения сущности */
    const TABLE_NAME = 'information_property_information_domain';

    /** @var string имя таблицы БД для хранения сущности */
    protected $tablename = self::TABLE_NAME;
    
    /** @var string имя одной таблицы */
    const LEFT = InformationDomain::EXTERNAL_ID;
    /** @var string имя другой таблицы */
    const RIGHT = InformationProperty::EXTERNAL_ID;

    /** @var string имя левой таблицы */
    protected $leftColumn = self::LEFT;
    /** @var string имя правой таблицы */
    protected $rightColumn = self::RIGHT;

    public $leftId = self::EMPTY_VALUE;
    public $rightId = self::EMPTY_VALUE;


}
