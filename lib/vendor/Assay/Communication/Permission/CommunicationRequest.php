<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 12:01
 */
namespace Assay\Communication\Permission {
    class CommunicationRequest implements ICommunicationRequest
    {
        public $session;
        public $process;
        public $object;
        public $content;

        public function testPrivilege():bool
        {

        }
    }
}