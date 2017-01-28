<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:40
 */
namespace Assay\Communication\Profile {

    use Assay\Core\LinkageEntity;

    class ProfileFeature extends LinkageEntity
    {
        /** @var string колонка ссылки на профиль пользователя */
        const PROFILE = IPersonProfile::EXTERNAL_ID;

        /** @var string профиль пользователя */
        public $profile;
    }
}
