<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:10
 */
namespace Assay\Permission\Privilege {
    class ProcessRequest implements IProcessRequest
    {
        /** @var string сессия */
        public $session;
        /** @var string процесс */
        public $process;
        /** @var string объект */
        public $object;
        /** @var string содержание процесса */
        public $content;

        public function testPrivilege():bool
        {

        }
    }
}