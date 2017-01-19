<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:56
 */
namespace Assay\InformationsCatalog\Permission {

    use Assay\Core\NamedEntity;
    /**
     * Процесс структуры каталога
     */
    class StructureProcess extends NamedEntity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'structure_operation_id';
    }
}
