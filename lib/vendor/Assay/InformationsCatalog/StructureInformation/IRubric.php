<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:11
 */
namespace Assay\InformationsCatalog\StructureInformation {
    interface IRubric
    {
        const EXTERNAL_ID = 'rubric_id';

        public function getMap():array;

        public function getSearchParameters():array;

        public function getProperties():array;
    }
}
