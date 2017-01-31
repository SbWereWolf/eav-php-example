<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:46
 */
namespace Assay\InformationsCatalog\RedactorContent {
    /**
     * Функционал работы с пользовательскими данными
     */
    interface IAdditionalValue
    {
        /** Получить свойства цены доставки
         * @return array свойства цены
         */
        public function getShippingPricing():array;

        /** Получить свойства цены товара
         * @return array свойства цены
         */
        public function getGoodsPricing():array;
    }
}
