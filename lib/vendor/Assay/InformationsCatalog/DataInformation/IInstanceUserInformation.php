<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:46
 */
namespace Assay\InformationsCatalog\DataInformation {
    interface IInstanceUserInformation
    {
        public function GetShippingPricing():array;

        public function GetGoodsPricing():array;

        public function GetCompanyRubrics():array;

    }
}