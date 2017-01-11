<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:48
 */
namespace Assay\InformationsCatalog\DataInformation {
    /**
     * Работа с формой документа
     */
    interface IDocumentForm
    {
        /** Получить значения для плейсхолдеров
         * @return array значения для плейсхолдеров
         */
        public function getFormPlaceholderValue():array;
        /** Получить форму документа
         * @param string $code код формы
         * @return array параметры формы документа
         */
        public function getDocumentForm(string $code):array;
    }
}
