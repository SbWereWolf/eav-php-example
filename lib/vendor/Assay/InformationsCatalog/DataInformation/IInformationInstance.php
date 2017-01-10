<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:47
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\InformationsCatalog\StructureInformation\TypeEdit;

    interface IInformationInstance
    {
        const EXTERNAL_ID = 'information_instance_id';

        const RUBRIC = 'rubric_id';

        public function getPositionByPrivileges($type = TypeEdit::Undefined):array;

        public function search(array $filterProperties, int $start, int $paging):array;
    }
}
