<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:38
 */
namespace Assay\Communication\Profile {
    interface IProfile
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const GREETINGS_ROLE = 'greetings_role';

        public function getCommentEnableArea():bool;

        public function getGreetingsRole():string;
    }
}