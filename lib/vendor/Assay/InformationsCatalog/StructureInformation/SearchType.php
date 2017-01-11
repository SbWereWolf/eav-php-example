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

        /** @var string значение не определено */
        const UNDEFINED = '0';
        /** @var string поиск подобия */
        const LIKE = '1';
        /** @var string поиск в диапазоне */
        const BETWEEN = '2';
        /** @var string поиск перечисления */
        const ENUMERATION = '3';
    }
}
