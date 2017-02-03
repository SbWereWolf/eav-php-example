<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:11
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\InformationsCatalog\DataInformation\RubricPosition;

    /**
     * Функционал рубрики каталога
     */
    interface IRubric
    {
        
        /** Получить описания позиций рубрики
         * @param string $codeKey индекс для элементов массива
         * @return array позиции
         */
        public function getMap(string $codeKey = RubricPosition::CODE):array;
        /** Получить параметры поиска по рубрике
         * @return array параметры поиска
         */
        public function getSearchParameters():array;

        /** Получить свойства рубрики
         * @param string $property индекс для кода рубрики
         * @param string $typeEdit индекс для кода типа редактирования
         * @param string $searchType индекс для кода типа поиска
         * @param string $dataType индекс для кода типа данных
         * @return array свойства рубрики
         */
        public function getProperties(string $property = InformationProperty::TABLE_NAME,
                                      string $typeEdit= TypeEdit::TABLE_NAME,
                                      string $searchType= SearchType::TABLE_NAME,
                                      string $dataType= DataType::TABLE_NAME):array;

        /** Добавить позицию
         * @return string идентификатор добавленной позиции
         */
        public function addPosition():string;

        /** Добавить свойство
         * @param string $code код свойства
         * @return bool успех выполнения
         */
        public function addProperty(string $code):bool;

        /** Скрыть свойство
         * @param string $code код свойства 
         * @return bool успех выполнения
         */
        public function dropProperty(string $code):bool;

        /** Выполнить поиск
         * @param array $filterProperties параметры поиска
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public function search(array $filterProperties, int $start, int $paging):array;
    }
}
