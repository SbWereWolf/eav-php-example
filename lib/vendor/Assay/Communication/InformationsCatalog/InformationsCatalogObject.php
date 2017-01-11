<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 12:06
 */
namespace Assay\Communication\InformationsCatalog {

    use Assay\Core\Entity;

    class InformationsCatalogObject extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'information_catalog_object_id';
    }
}