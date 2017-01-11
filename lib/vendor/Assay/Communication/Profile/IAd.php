<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:37
 */
namespace Assay\Communication\Profile {
    interface IAd
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'ad_id';

        /** @var string колонка ссылки на социальный объект */
        const SOCIAL_OBJECT = SocialObject::EXTERNAL_ID;

        const CONTENT = 'content';
        const UPDATE_DATE = 'update_date';

        public function purge():bool;
    }
}