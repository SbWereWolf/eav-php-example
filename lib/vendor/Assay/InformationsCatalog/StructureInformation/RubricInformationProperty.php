<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20.01.2017
 * Time: 19:46
 */

namespace Assay\InformationsCatalog\StructureInformation;


use Assay\Core\InnerLinkageEntity;

/**
 * Стыковка Рубрики и информационного свойства
 * LEFT => Rubric, RIGHT => InformationProperty
 */
class RubricInformationProperty extends InnerLinkageEntity
{
    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'rubric_Information_property_id';

    /** @var string имя таблицы БД для хранения сущности */
    const TABLE_NAME = 'rubric_Information_property';

    /** @var string колонка для внешнего ключа ссылки на рубрику */
    const LEFT = Rubric::EXTERNAL_ID;
    /** @var string колонка для внешнего ключа ссылки на свойство рубрики  */
    const RIGHT = InformationProperty::EXTERNAL_ID;
    
    /** @var string имя таблицы БД для хранения сущности */
    protected $tablename = self::TABLE_NAME;

    protected $leftColumn = self::LEFT;
    /** @var string имя правой таблицы */
    protected $rightColumn = self::RIGHT;

}
