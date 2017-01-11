<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:57
 */
namespace Assay\InformationsCatalog\Permission {

    use Assay\Core\Entity;
    /**
     * Разрешение структуры
     */
    class StructurePrivilege extends Entity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'structure_privilege_id';

        /** @var string колонка для ссылки на процесс структуры */
        const OPERATION = StructureProcess::EXTERNAL_ID;
        /** @var string колонка для ссылки на объект структуры */
        const OBJECT = StructureObject::EXTERNAL_ID;
        /** @var string колонка для ссылки на разрешение инфомационного каталога  */
        const INFORMATION_PRIVILEGE = InformationPrivilege::EXTERNAL_ID;
    }
}
