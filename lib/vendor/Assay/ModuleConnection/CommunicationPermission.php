<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.01.2017
 * Time: 22:41
 */

namespace Assay\ModuleConnection;


use Assay\Communication\InformationsCatalog\CommunicationObject;

class CommunicationPermission
{
    /** @var string колонка внешнего ключа для ссылки на эту таблицу */
    const EXTERNAL_ID = 'communication_permission_id';
    /** @var string название таблицы для хранения данных сущности */
    const TABLE_NAME = 'communication_permission';

    /** @var string колонка для ссылки на объект общения */
    const COMMUNICATION = CommunicationObject::EXTERNAL_ID;
    /** @var string колонка для ссылки на объект разрешений */
    const PERMISSION = PermissionObject::EXTERNAL_ID;

    /** @var string назнвание таблицы для хранения данных сущности */
    protected $tablename = self::TABLE_NAME;
}
