<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:41
 */
namespace Assay\Communication\Profile {
    class Ad extends ProfileFeature implements IAd
    {

        public $content;
        public $social_object;

        public function purge():bool
        {
        }
    }
}