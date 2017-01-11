<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:10
 */
namespace Assay\Permission\Privilege {
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
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public static function getCompanyFilter():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public static function getMode():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public static function getPaging():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public static function getUserName():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public function setKey():bool
        {
        }

        public function setCompanyFilter():bool
        {
        }

        public function setMode():bool
        {
        }

        public function setPaging():bool
        {
        }

        public function setUserName():bool
        {
        }
    }
}