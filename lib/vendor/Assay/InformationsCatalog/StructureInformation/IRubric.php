<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:11
 */
namespace Assay\InformationsCatalog\StructureInformation {
    /**
     * Функционал рубрики каталога
     */
    interface IRubric
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'rubric_id';

        /** Получить описания позиций рубрики
         * @return array позиции
         */
        public function getMap():array;
        /** Получить параметры поиска по рубрике
         * @return array параметры поиска
         */
        public function getSearchParameters():array;
        /** Получить свойства рубрики
         * @return array свойства рубрики
         */
        public function getProperties():array;
    }
}
