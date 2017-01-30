<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 12:06
 */
namespace Assay\Communication\InformationsCatalog {

    use Assay\Core\Entity;
    /**
     * Объект информационного каталога
     */
    class InformationObject extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'information_object_id';

        /** @var string назнвание таблицы для хранения данных сущности */
        const TABLE_NAME = 'information_object';

        /** @var string назнвание таблицы для хранения данных сущности */
        protected $tablename = self::TABLE_NAME;
    }
}
