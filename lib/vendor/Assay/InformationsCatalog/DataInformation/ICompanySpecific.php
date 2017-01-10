<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:49
 */
namespace Assay\InformationsCatalog\DataInformation {
    interface ICompanySpecific
    {
        public function GetMap():array;

        public function GetAddress():array;
    }
}