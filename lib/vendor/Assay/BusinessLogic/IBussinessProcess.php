<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 18.01.17
 * Time: 15:55
 */
namespace Assay\BusinessLogic {
    interface IBussinessProcess
    {
        /** @var string режим */
        const MODE = 'mode';

        public function getMode():string;
    }
}