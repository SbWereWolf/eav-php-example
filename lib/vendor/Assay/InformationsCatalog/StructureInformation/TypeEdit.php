<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:12
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\NamedEntity;
    /**
     * Тип редактирования позиции рубрики
     */
    class TypeEdit extends NamedEntity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'type_edit_id';

        /** @var string имя таблицы */
        const TABLE_NAME = 'type_edit';

        /** @var string значение не определено */
        const UNDEFINED = 'UNDEFINED';
        /** @var string системное свойство */
        const SYSTEM = 'SYSTEM';
        /** @var string пользовательское свойство */
        const USER = 'USER';
        /** @var string свойство компании */
        const COMPANY = 'COMPANY';

        /** @var string имя таблицы */
        protected $tablename = self::TABLE_NAME;
    }
}
