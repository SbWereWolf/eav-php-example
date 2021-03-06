<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:10
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\NamedEntity;

    class DataType extends NamedEntity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'data_type_id';

        /** @var string имя таблицы */
        const TABLE_NAME = 'data_type';

        /** @var string значение не определено */
        const UNDEFINED = 'UNDEFINED';
        /** @var string числовой тип */
        const DIGITAL = 'DIGITAL';
        /** @var string символьный тип */
        const STRING = 'STRING';
        /** @var string тип для даты и времени */
        const DATETIME = 'DATETIME';

        /** @var string имя таблицы */
        protected $tablename = self::TABLE_NAME;
    }
}
