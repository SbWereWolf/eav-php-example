<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:09
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\ICommon;
    use Assay\Core\NamedEntity;
    /**
     * Древовидная структура
     */
    class Structure extends NamedEntity implements IStructure    
    {
        /** @var string родительский элемент */
        public $parent = ICommon::EMPTY_VALUE;
        /** Добавить дочерний элемент
         * @return string идентификатор добавленого элемента
         */
        public function addChild():string
        {

        }
        /** Получить имена дочерних элементов
         * @return array имена элементов
         */
        public function getChildrenNames():array
        {

        }
        /** Получить получить идентификатор ролительского элемнта
         * @return string идентификатор
         */
        public function getParent():int
        {

        }
        /** Проверить что является разделом
         * @return bool успех проверки
         */
        public function isPartition():bool
        {

        }
        /** Проверить что является рубрикой
         * @return bool успех проверки
         */
        public function isRubric():bool
        {

        }
        /** Получить путь от этого элемента до корневого
         * @return array элменты пути
         */
        public function getPath():array
        {

        }
        /** Получить описание всех элементов
         * @return array элменты пути
         */
        public function getMap():array
        {

        }
        /** Выполнить поиск
         * @param string $searchString поисковая строка
         * @param string $structureCode код корневого элемента
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public function search(string $searchString= ICommon::EMPTY_VALUE, string $structureCode = ICommon::EMPTY_VALUE, int $start, int $paging):array{

        }
    }
}
