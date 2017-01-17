<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:35
 */
namespace Assay\Communication\Profile {
    interface IPersonProfile
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'person_profile_id';

        /** @var string колонка ссылки на социальный объект */
        const OBJECT = SocialObject::EXTERNAL_ID;

        public function getForGreetings():string;

        public function enableCommenting():bool;

        public function testPrivilege():bool;

        public function purgeGroup():bool;

        public function setGroup():bool;
    }
}