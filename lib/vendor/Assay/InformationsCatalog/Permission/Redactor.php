<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:56
 */
namespace Assay\InformationsCatalog\Permission {

    use Assay\Core\ChildEntity;
    /**
     * Пользователь информационного каталога
     */
    class Redactor extends ChildEntity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'redactor_id';

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'redactor';

        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;
    }
}
