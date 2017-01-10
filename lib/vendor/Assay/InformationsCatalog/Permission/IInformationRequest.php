<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:57
 */
namespace Assay\InformationsCatalog\Permission {
    interface IInformationRequest
    {
        public function TestPrivilege():bool;
    }
}