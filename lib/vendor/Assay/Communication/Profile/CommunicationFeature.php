<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:41
 */
namespace Assay\Communication\Profile {

    use Assay\Core\MutableEntity;

    class CommunicationFeature extends MutableEntity
    {
        /** @var string колонка ссылки на профиль пользователя */
        const PROFILE = IPersonProfile::EXTERNAL_ID;

        public $profile;
    }
}