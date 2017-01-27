<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:11
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\InformationsCatalog\DataInformation\InformationInstance;

    /**
     * Функционал рубрики каталога
     */
    interface IRubric
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'rubric_id';

        /** Получить описания позиций рубрики
         * @param string $codeKey индекс для элементов массива
         * @return array позиции
         */
        public function getMap(string $codeKey = InformationInstance::CODE):array;
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
        public function getProperties(string $property = IInformationProperty::TABLE_NAME,
                                      string $typeEdit= TypeEdit::TABLE_NAME,
                                      string $searchType= SearchType::TABLE_NAME,
                                      string $dataType= DataType::TABLE_NAME):array;
    }
}
