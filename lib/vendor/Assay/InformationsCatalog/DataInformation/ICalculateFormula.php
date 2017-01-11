<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:48
 */
namespace Assay\InformationsCatalog\DataInformation {
    /**
     * Функционал вычисления формулы
     */
    interface ICalculateFormula
    {
        /** Получить аргументы формулы
         * @return array аргументы
         */
        public function getFormulaArgumentValue():array;
        /** Вычислить формулу
         * @param array $arguments значения аргументов
         * @return array результат вычисления
         */
        public function getFormulaResult(array $arguments):array;
    }
}
