<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:47
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\InformationsCatalog\StructureInformation\DataType;
    use Assay\InformationsCatalog\StructureInformation\InformationProperty;
    use Assay\InformationsCatalog\StructureInformation\TypeEdit;

    /**
     * Вычисление формулы
     */
    interface IRubricPosition
    {

        /** Получить позицию для отображения
         * @param string $propertyName индекс для Названия свойства
         * @param string $propertyDescription индекс для описания свойства
         * @param string $typeEdit индекс для типа редактирования
         * @param string $dataType индекс для типа данных
         * @param string $content индекс для значения
         * @return array массив с набором свойств
         */
        public function getPosition(string $propertyName = InformationProperty::NAME,
                                    string $propertyDescription = InformationProperty::DESCRIPTION,
                                    string $typeEdit = TypeEdit::EXTERNAL_ID,
                                    string $dataType = DataType::EXTERNAL_ID,
                                    string $content = PropertyContent::CONTENT):array;

        /** Выполнить поиск
         * @param array $filterProperties параметры поиска
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public function search(array $filterProperties, int $start, int $paging):array;

        /** сохранить содержание свойства
         * @param string $content содержимое свойства
         * @param string $code код свойства
         * @return bool успех выполнения
         */
        public function saveContent(string $content, string $code):bool;

        /** Сохранить дополнительное значение
         * @param string $value дополнительное значение свойства 
         * @param string $code код свойства
         * @param string $redactorId идентификатор редактора
         * @return string идентификатор добавленной позиции
         */
        public function saveValue(string $value, string $code,  string $redactorId):string;

    }
}
