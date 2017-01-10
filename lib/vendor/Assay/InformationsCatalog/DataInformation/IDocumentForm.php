<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:48
 */
namespace Assay\InformationsCatalog\DataInformation {
    interface IDocumentForm
    {
        public function GetFormPlaceholderValue():array;

        public function GetDocumentForm(string $code):array;
    }
}