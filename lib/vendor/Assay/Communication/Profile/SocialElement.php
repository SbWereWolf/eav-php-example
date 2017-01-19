<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:40
 */
namespace Assay\Communication\Profile {
    class SocialElement extends ProfileFeature implements ISocialElement
    {
        /** @var string социальный объект */
        public $object;

        public function count():int
        {
        }

        public function isOwn():bool
        {
        }
    }
}