<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:39
 */
namespace Assay\Communication\Profile {

    use Assay\Core\NamedEntity;

    class SocialGroup extends NamedEntity implements ISocialGroup
    {
        /** Является ли пользователь членом группы
         * добавить id пользователя ?
         * @return bool
         */
        public function isMember():bool
        {
        }
    }
}