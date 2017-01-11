<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:12
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\NamedEntity;
    /**
     * Домен свойства
     */
    class InformationDomain extends NamedEntity implements IInformationDomain
    {
        /** @var string тип редактирования */
        public $typeEdit;
        /** @var string тип поиска */
        public $searchType = SearchType::UNDEFINED;

        public function getSearchParameters():array
        {

        }

    }
}
