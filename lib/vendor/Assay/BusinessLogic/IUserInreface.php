<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 18.01.17
 * Time: 15:55
 */

namespace Assay\BusinessLogic {
    interface IUserInreface
    {
        /** @var string пагинация */
        const PAGING = 'pagging';
        /** @var string фильтр по компании */
        const COMPANY_FILTER = 'company_filter';
        /** @var string режим */
        const MODE = 'mode';

        public function getPaging():string;

        public function getGreetingsRole(): string;

        public function getMode(): string;

        public function getProfileId(): string;

        public function getUserId(): string;

        public function getCompanyFilter():string;
    }
}