<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.01.2017
 * Time: 22:36
 */

namespace Assay\ModuleConnection;


use Assay\Core\Record;

class PermissionObject extends Record
{
    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'permission_object_id';

    /** @var string имя таблицы для хранения данных сущности */
    const TABLE_NAME = 'permission_object';

    /** @var string назнвание таблицы для хранения данных сущности */
    protected $tablename = self::TABLE_NAME;
}

