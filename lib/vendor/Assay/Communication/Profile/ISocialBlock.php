<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:37
 */
namespace Assay\Communication\Profile {
    interface ISocialBlock
    {
        public function getCounter():array;

        public function getComment():array;
    }
}