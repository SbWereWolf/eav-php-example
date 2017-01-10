<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:13
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\Entity;

    class RubricProperty extends Entity
    {
        const EXTERNAL_ID = 'rubric_property_id';

        const PROPERTY = IInformationDomain::EXTERNAL_ID;
        const RUBRIC = Rubric::EXTERNAL_ID;
    }
}