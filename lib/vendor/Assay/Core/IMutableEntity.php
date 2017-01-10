<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:02
 */
namespace Assay\Core {
    interface IMutableEntity
    {
        public function MutateEntity():bool;

        public function ToEntity():array;

    }
}