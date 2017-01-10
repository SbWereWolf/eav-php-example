<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:03
 */
namespace Assay\Core {
    interface INamedEntity
    {
        const CODE = 'code';
        const NAME = 'name';
        const DESCRIPTION = 'description';

        public function LoadByCode(string $code):array;

        public function GetElementDescription():array;

    }
}