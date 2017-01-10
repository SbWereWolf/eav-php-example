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
        public function getFormPlaceholderValue():array;

        public function getDocumentForm(string $code):array;
    }
}
