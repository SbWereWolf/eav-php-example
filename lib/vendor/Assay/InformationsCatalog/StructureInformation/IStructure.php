<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:08
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\ICommon;
    use Assay\Core\INamedEntity;

    /**
     * Функционал работы со структурой
     */
    interface IStructure
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'structure_id';

        /** @var string родительский элемент */
        const PARENT = self::EXTERNAL_ID;

        /** Добавить дочерний элемент
         * @return string идентификатор добавленого элемента
         */
        public function addChild():string ;

        /** Получить имена дочерних элементов
         * @param string $nameKey имя индекса для имени дочернего элемента структуры
         * @return array имена элементов
         */
        public function getChildrenNames(string $nameKey = INamedEntity::NAME):array;

        /** Получить получить идентификатор ролительского элемнта
         * @return string идентификатор
         */
        public function getParent():string;

        /** Проверить что является разделом
         * @return bool успех проверки
         */
        public function isPartition():bool;
        /** Проверить что является рубрикой
         * @return bool успех проверки
         */
        public function isRubric():bool;

        /** Получить путь от этого элемента до корневого
         * @return array элменты пути
         */
        public function getPath():array;

        /** Получить описание всех дочерних элементов элементов
         * @param string $code код робительского элемента
         * @return array элменты пути
         */
        public static function getMap(string $code = ' '):array;

        /** Выполнить поиск
         * @param string $searchString поисковая строка
         * @param string $structureCode код корневого элемента
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public static function search(string $searchString= ICommon::EMPTY_VALUE
            , string $structureCode = ICommon::EMPTY_VALUE
            , int $start
            , int $paging):array;

        /** Получить коды элементов для которых этот родительский
         * @param string $codeKey наименование индекса для кода дочернего элемента структуры
         * @return array
         */
        public function getChildrenCodes(string $codeKey = INamedEntity::CODE):array;

        /** Добавить запись в БД на основе экземпляра
         * @param string $parentCode
         * @return bool успех выполнения
         */
        public function setParent(string $parentCode):bool;
    }
}
