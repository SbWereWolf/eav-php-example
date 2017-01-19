<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:56
 */
namespace Assay\InformationsCatalog\Permission {

    use Assay\Core\Entity;
    use Assay\InformationsCatalog\StructureInformation\IStructure;
    /**
     * Объект структуры
     */
    class StructureObject extends Entity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'structure_object_id';

        /** @var string колонка для ссылки на элемент структуры */
        const STRUCTURE = IStructure::EXTERNAL_ID;
    }
}
