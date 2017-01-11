<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 11:56
 */
namespace Assay\Communication\Permission {
    interface ICommunicationRequest
    {
        public function testPrivilege():bool;
    }
}