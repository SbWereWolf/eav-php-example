<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:08
 */
namespace Assay\Permission\Privilege {

    use Assay\BusinessLogic\IBussinessProcess;
    use Assay\BusinessLogic\IUserInreface;
    use Assay\Communication\Profile\IProfile;
    use Assay\Core\Common;

    interface ISession
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'session_id';

        /** @var string номер сессии */
        const KEY = 'key';
        const COMPANY_FILTER = IUserInreface::COMPANY_FILTER;
        const MODE = IBussinessProcess::MODE;
        const PAGING = IUserInreface::PAGING;
        const GREETINGS_ROLE = IProfile::GREETINGS_ROLE;

        const USER_ID = 'account_id';

        const OPEN_PROCESS = 'open_session';
        const SESSION_OBJECT = 'session';

        public static function open(string $userId):array ;

        public function setSession():bool;

        public function loadByKey():array;

        public function close():bool;
    }
}