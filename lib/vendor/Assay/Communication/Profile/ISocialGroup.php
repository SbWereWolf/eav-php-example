<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:35
 */
namespace Assay\Communication\Profile {
    interface ISocialGroup
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'social_group_id';

        /** @var string колонка ссылки на социальный объект */
        const OBJECT = SocialObject::EXTERNAL_ID;

        public function isMember():bool;
    }
}