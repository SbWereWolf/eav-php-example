<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:11
 */
namespace Assay\InformationsCatalog\StructureInformation {
    
    use Assay\Core\InnerLinkageEntity;

    class RubricStructure extends InnerLinkageEntity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'rubric_structure_id';

        /** @var string имя таблицы для хранения сущности */
        const TABLE_NAME = 'rubric_structure';

        /** @var string колонка для внешнего ключа ссылки на рубрику */
        const LEFT = Rubric::EXTERNAL_ID;
        /** @var string колонка для внешнего ключа ссылки на структуру */
        const RIGHT = Structure::EXTERNAL_ID;

        /** @var string имя таблицы для хранения сущности */
        protected $tablename = self::TABLE_NAME;
        /** @var string имя левой таблицы */
        protected $leftColumn = self::LEFT;
        /** @var string имя правой таблицы */
        protected $rightColumn = self::RIGHT;

        public $leftId = self::EMPTY_VALUE;
        public $rightId = self::EMPTY_VALUE;
    }
}
