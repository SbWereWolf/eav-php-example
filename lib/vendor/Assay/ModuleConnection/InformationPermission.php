<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.01.2017
 * Time: 21:17
 */

namespace Assay\InformationsCatalog\Permission;


use Assay\Communication\InformationsCatalog\InformationObject;
use Assay\ModuleConnection\PermissionObject;

class InformationPermission
{
    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'information_permission_id';

    /** @var string назнвание таблицы для хранения данных сущности */
    const TABLE_NAME = 'information_permission';

    /** @var string колонка для ссылки на информациионный объект */
    const INFORMATION = InformationObject::EXTERNAL_ID;
    /** @var string колонка для ссылки на объект разрешений */
    const PERMISSION = PermissionObject::EXTERNAL_ID;

    /** @var string назнвание таблицы для хранения данных сущности */
    protected $tablename = self::TABLE_NAME;
}
