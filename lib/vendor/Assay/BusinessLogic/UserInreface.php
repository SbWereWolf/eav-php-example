<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 18.01.17
 * Time: 15:55
 */
namespace Assay\BusinessLogic {

    use Assay\Core\Common;

    class UserInreface implements IUserInreface
    {
        public function getPagging(): string
        {
            $result = Common::EMPTY_VALUE;
            return $result;
        }

        public function getCompanyFilter(): string
        {
            $result = Common::EMPTY_VALUE;
            return $result;
        }
    }
}