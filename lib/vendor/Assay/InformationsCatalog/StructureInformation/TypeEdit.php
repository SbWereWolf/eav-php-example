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

        /** @var string значение не определено */
        const UNDEFINED = '0';
        /** @var string системные свойства */
        const SYSTEM = '1';
        /** @var string пользовательские свойства */
        const USER = '2';
        /** @var string свойства компании */
        const COMPANY = '3';
    }
}
