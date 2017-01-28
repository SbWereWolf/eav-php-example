<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.01.2017
 * Time: 0:33
 */

namespace Assay\ModuleConnection;


use Assay\Permission\Privilege\Account;

class AccountObject
{
    /** @var string колонка внешнего ключа для ссылки на эту таблицу */
    const EXTERNAL_ID = 'account_object_id';

    /** @var string колонка внешнего ключа для ссылки на социальную группу */
    const SOCIAL_GROUP = Account::EXTERNAL_ID;
    /** @var string колонка внешнего ключа для ссылки на объект общения */
    const COMMUNICATION = PermissionObject::EXTERNAL_ID;

    /** @var string назнвание таблицы для хранения данных сущности */
    const TABLE_NAME = 'account_object';

    /** @var string назнвание таблицы для хранения данных сущности */
    protected $tablename = self::TABLE_NAME;
}

