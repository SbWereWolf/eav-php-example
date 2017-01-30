<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:56
 */
namespace Assay\InformationsCatalog\RedactorContent {

    use Assay\Core\NamedEntity;

    /**
     * Пользователь информационного каталога
     */
    class Redactor extends NamedEntity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'redactor_id';

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'redactor';

        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;
    }
}
