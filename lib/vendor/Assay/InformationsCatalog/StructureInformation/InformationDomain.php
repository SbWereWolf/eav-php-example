<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:12
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\NamedEntity;

    class InformationDomain extends NamedEntity implements IInformationDomain
    {
        public $typeEdit;
        public $searchType = SearchType::Undefined;

        public function getSearchParameters():array
        {

        }

    }
}
