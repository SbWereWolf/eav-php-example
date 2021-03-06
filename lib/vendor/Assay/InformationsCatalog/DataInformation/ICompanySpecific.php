<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:49
 */
namespace Assay\InformationsCatalog\DataInformation {
    /**
     * Функционал специфичный для компании
     */
    interface ICompanySpecific
    {
        /** Получить описание рубрик компании
         * @return array рубрики компании
         */
        public function getMap():array;
        /** Получить описание адресов компании
         * @return array адреса компании
         */
        public function getAddress():array;
    }
}
