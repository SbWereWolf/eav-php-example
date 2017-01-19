<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:09
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\NamedEntity;

    class BusinessRole extends NamedEntity
    {
        /** @var string название таблицы */
        const TABLE_NAME = 'role';
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'role_id';
    }
}