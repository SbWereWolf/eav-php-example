<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:13
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\Entity;
    /**
     * Свойство рубрики
     */
    class RubricProperty extends Entity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'rubric_property_id';

        /** @var string колонка для внешнего ключа ссылки на информационный домен */
        const DOMAIN = IInformationDomain::EXTERNAL_ID;
        /** @var string колонка для внешнего ключа ссылки на рубрику */
        const RUBRIC = Rubric::EXTERNAL_ID;
    }
}
