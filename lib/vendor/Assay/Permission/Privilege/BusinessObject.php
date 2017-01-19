<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:08
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\NamedEntity;

    class BusinessObject extends NamedEntity
    {
        /** @var string название таблицы */
        const TABLE_NAME = 'business_object';
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'business_object_id';
    }
}