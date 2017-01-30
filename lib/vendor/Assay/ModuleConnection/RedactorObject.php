<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.01.2017
 * Time: 20:52
 */

namespace Assay\InformationsCatalog\Permission;


use Assay\Communication\InformationsCatalog\InformationObject;
use Assay\InformationsCatalog\RedactorContent\Redactor;

class RedactorObject
{
    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'redactor_object_id';

    /** @var string назнвание таблицы для хранения данных сущности */
    const TABLE_NAME = 'redactor_object';

    /** @var string колонка для ссылки на информационный объект */
    const INFORMATION= InformationObject::EXTERNAL_ID;
    /** @var string колонка для ссылки на редактора */
    const REDACTOR = Redactor::EXTERNAL_ID;
    

    /** @var string назнвание таблицы для хранения данных сущности */
    protected $tablename = self::TABLE_NAME;
}
