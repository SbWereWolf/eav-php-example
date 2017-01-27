<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:11
 */
namespace Assay\InformationsCatalog\StructureInformation {
    
    use Assay\Core\LinkageEntity;

    class LinkRubricStructure extends LinkageEntity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'rubric_structure_id';

        /** @var string имя таблицы для хранения сущности */
        const TABLE_NAME = 'rubric_structure';

        /** @var string колонка для внешнего ключа ссылки на рубрику */
        const RUBRIC = Rubric::EXTERNAL_ID;
        /** @var string колонка для внешнего ключа ссылки на структуру */
        const STRUCTURE = Structure::EXTERNAL_ID;

        /** @var string имя таблицы для хранения сущности */
        protected $tablename = self::TABLE_NAME;
    }
}
