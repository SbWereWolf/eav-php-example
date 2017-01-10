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
        const EXTERNAL_ID = 'link_rubric_structure_id';

        const RUBRIC = Rubric::EXTERNAL_ID;
        const STRUCTURE = Structure::EXTERNAL_ID;
    }
}