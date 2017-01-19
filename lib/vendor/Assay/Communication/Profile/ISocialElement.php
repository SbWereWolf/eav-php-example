<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:36
 */
namespace Assay\Communication\Profile {
    interface ISocialElement
    {
        /** @var string колонка ссылки на социальный объект */
        const SOCIAL_OBJECT = SocialObject::EXTERNAL_ID;

        public function count():int;

        public function isOwn():bool;
    }
}