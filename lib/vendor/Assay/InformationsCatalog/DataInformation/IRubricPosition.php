<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:47
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\InformationsCatalog\RedactorContent\AdditionalValue;
    use Assay\InformationsCatalog\StructureInformation\DataType;
    use Assay\InformationsCatalog\StructureInformation\InformationProperty;
    use Assay\InformationsCatalog\StructureInformation\TypeEdit;

    /**
     * Вычисление формулы
     */
    interface IRubricPosition
    {

        const TRANSPORTATION_CODE = 'TRANSPORTATION';
        const GOODS_PRISING_CODE = 'GOODS_PRISING';

        /** Получить позицию для отображения
         * @param string $propertyCode код свойства
         * @param string $content индекс для значения
         * @param string $typeEdit индекс для типа редактирования
         * @param string $dataType индекс для типа данных
         * @return array массив с набором свойств
         */
        public function getPositionContent(
            string $propertyCode = InformationProperty::TABLE_NAME,
            string $content = PropertyContent::CONTENT,
            string $typeEdit = TypeEdit::EXTERNAL_ID,
            string $dataType = DataType::EXTERNAL_ID):array;

        /** Получить позицию для отображения
         * @param string $property код свойства
         * @param string $typeEdit индекс для типа редактирования
         * @param string $dataType индекс для типа данных
         * @param string $value индекс для значения
         * @return array массив с набором свойств
         */
        public function getPositionValue(
            string $property = InformationProperty::CODE,
            string $value = AdditionalValue::VALUE,
            string $typeEdit = TypeEdit::EXTERNAL_ID,
            string $dataType = DataType::EXTERNAL_ID
        ):array;

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
        public function saveValue(string $value, string $code, string $redactorId):string;

        /** Получить свойства цены доставки
         * @param string $value индекс для дополнительных значений
         * @param string $code индекс для кода свойства
         * @param string $redactor индекс для идентификатора редактора
         * @return array свойства цены
         */
        public function getShippingPricing(
            string $value = AdditionalValue::VALUE,
            string $code = InformationProperty::CODE,
            string $redactor = AdditionalValue::REDACTOR):array;

        /** Получить свойства цены товара
         * @param string $value индекс для дополнительных значений
         * @param string $code индекс для кода свойства
         * @param string $redactor индекс для идентификатора редактора
         * @return array свойства цены
         */
        public function getGoodsPricing(
            string $value = AdditionalValue::VALUE,
            string $code = InformationProperty::CODE,
            string $redactor = AdditionalValue::REDACTOR):array;
    }

}
