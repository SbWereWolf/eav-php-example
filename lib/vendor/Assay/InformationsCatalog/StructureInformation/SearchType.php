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
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'search_type_id';

        /** @var string имя таблицы */
        const TABLE_NAME = 'search_type';

        /** @var string значение не определено */
        const UNDEFINED = 'UNDEFINED';
        /** @var string поиск подобия */
        const LIKE = 'LIKE';
        /** @var string поиск в диапазоне */
        const BETWEEN = 'BETWEEN';
        /** @var string поиск перечисления */
        const ENUMERATION = 'ENUMERATION';

        /** @var string имя таблицы */
        protected $tablename = self::TABLE_NAME;
    }
}
