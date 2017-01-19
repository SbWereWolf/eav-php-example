<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:10
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Common;

    class Cookie implements ICookies
    {
        public $key;
        public $companyFilter;
        public $mode;
        public $paging;
        public $userName;

        public function __construct()
        {
            $this->key = self::getKey();
            $this->companyFilter = self::getCompanyFilter();
            $this->mode = self::getMode();
            $this->paging = self::getPaging();
            $this->userName = self::getUserName();
        }

        public static function getKey():string
        {
            $result = Common::setIfExists(
                self::KEY, $_COOKIE, ICookies::EMPTY_VALUE
            );
            return $result;
        }

        public static function getCompanyFilter():string
        {
            $result = Common::setIfExists(
                self::COMPANY_FILTER, $_COOKIE, ICookies::EMPTY_VALUE
            );
            return $result;
        }

        public static function getMode():string
        {
            $result = Common::setIfExists(
                self::MODE, $_COOKIE, ICookies::EMPTY_VALUE
            );
            return $result;
        }

        public static function getPaging():string
        {
            $result = Common::setIfExists(
                self::PAGING, $_COOKIE, ICookies::EMPTY_VALUE
            );
            return $result;
        }

        public static function getUserName():string
        {
            $result = Common::setIfExists(
                self::USER_NAME, $_COOKIE, ICookies::EMPTY_VALUE
            );
            return $result;
        }

        public function setKey():bool
        {
            $result = true;
            var_dump($this->key);
            setcookie(self::KEY,$this->key,time()+self::COOKIES_TIME);
            return $result;
        }

        public function setCompanyFilter():bool
        {
            $result = true;
            setcookie(self::COMPANY_FILTER,$this->companyFilter,time()+self::COOKIES_TIME);
            return $result;
        }

        public function setMode():bool
        {
            $result = true;
            setcookie(self::MODE,$this->mode,time()+self::COOKIES_TIME);
            return $result;
        }

        public function setPaging():bool
        {
            $result = true;
            setcookie(self::PAGING,$this->paging,time()+self::COOKIES_TIME);
            return $result;
        }

        public function setUserName():bool
        {
            $result = true;
            setcookie(self::USER_NAME,$this->userName,time()+self::COOKIES_TIME);
            return $result;
        }
    }
}