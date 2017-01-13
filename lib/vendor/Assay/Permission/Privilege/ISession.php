<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:08
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Common;

    interface ISession
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'session_id';
        const EMPTY_VALUE = Common::EMPTY_VALUE;

        const KEY = ICookies::KEY;
        const COMPANY_FILTER = ICookies::COMPANY_FILTER;
        const MODE = ICookies::MODE;
        const PAGING = ICookies::PAGING;
        const USER_NAME = ICookies::USER_NAME;

        const USER_ID = 'user_id';

        const OPEN_PROCESS = 'open_session';
        const SESSION_OBJECT = 'session';

        public static function open(string $userId):array ;

        public function close():bool;
    }
}