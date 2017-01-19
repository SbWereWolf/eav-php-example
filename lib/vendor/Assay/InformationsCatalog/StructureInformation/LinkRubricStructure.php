<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:11
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\Entity;

    class LinkRubricStructure extends Entity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'link_rubric_structure_id';

        /** @var string колонка для внешнего ключа ссылки на рубрику */
        const RUBRIC = Rubric::EXTERNAL_ID;
        /** @var string колонка для внешнего ключа ссылки на структуру */
        const STRUCTURE = Structure::EXTERNAL_ID;
    }
}
