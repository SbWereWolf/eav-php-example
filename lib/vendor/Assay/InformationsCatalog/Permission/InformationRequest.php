<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:58
 */
namespace Assay\InformationsCatalog\Permission {
    class InformationRequest implements IInformationRequest
    {
        public $session;
        public $process;
        public $object;
        public $content;

        public function TestPrivilege():bool
        {

        }
    }
}