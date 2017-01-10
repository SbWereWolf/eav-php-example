<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:48
 */
namespace Assay\InformationsCatalog\DataInformation {
    interface ICalculateFormula
    {
        public function getFormulaArgumentValue():array;

        public function getFormulaResult(array $arguments):array;
    }
}
