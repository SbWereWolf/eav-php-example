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
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'user_object_id';
    }
}