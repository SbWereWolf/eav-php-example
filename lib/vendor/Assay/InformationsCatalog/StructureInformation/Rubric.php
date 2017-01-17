<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:11
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\NamedEntity;
    /**
     * Рубрика каталога
     */
    class Rubric extends NamedEntity implements IRubric
    {
        /** Получить описания позиций рубрики
         * @return array позиции
         */
        public function getMap():array
        {
        }
        /** Получить параметры поиска по рубрике
         * @return array параметры поиска
         */
        public function getSearchParameters():array
        {
        }
        /** Получить свойства рубрики
         * @return array свойства рубрики
         */
        public function getProperties():array
        {
        }
    }
}
