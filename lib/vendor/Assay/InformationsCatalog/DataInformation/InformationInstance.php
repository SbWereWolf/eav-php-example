<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:47
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\Core\NamedEntity;
    use Assay\InformationsCatalog\StructureInformation\TypeEdit;

    class InformationInstance extends NamedEntity implements IInformationInstance, IInstanceUserInformation
    {
        public $rubricId;

        public function getShippingPricing():array
        {
        }

        public function getGoodsPricing():array
        {
        }

        public function getCompanyRubrics():array
        {
        }

        public function getPositionByPrivileges($type = TypeEdit::Undefined):array
        {
        }

        public function search(array $filterProperties, int $start, int $paging):array
        {
        }

    }
}
