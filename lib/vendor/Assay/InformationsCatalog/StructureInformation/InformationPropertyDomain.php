<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.01.2017
 * Time: 22:53
 */

namespace Assay\InformationsCatalog\StructureInformation;


use Assay\Core\LinkageEntity;

class InformationPropertyDomain extends LinkageEntity
{

    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'information_property_information_domain_id';

    /** @var string имя таблицы БД для хранения сущности */
    const TABLE_NAME = 'information_property_information_domain';

    /** @var string колонка для внешнего ключа ссылки на информационный домен */
    const DOMAIN = IInformationDomain::EXTERNAL_ID;
    /** @var string колонка для внешнего ключа ссылки на свойство рубрики */
    const PROPERTY = InformationProperty::EXTERNAL_ID;
    /** @var string имя таблицы БД для хранения сущности */
    protected $tablename = self::TABLE_NAME;

}
