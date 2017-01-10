<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:12
 */
namespace Assay\InformationsCatalog\StructureInformation {
    interface IInformationDomain
    {
        const EXTERNAL_ID = 'information_property_id';

        const TYPE_EDIT = 'type_edit';
        const SEARCH_TYPE = 'search_type';

        public function getSearchParameters():array;

    }
}
