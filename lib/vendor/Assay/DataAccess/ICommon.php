<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19.01.2017
 * Time: 20:38
 */

namespace Assay\DataAccess;


interface ICommon
{
    /** @var string константа значение не задано для значимых типов */
    const EMPTY_VALUE = \Assay\Core\ICommon::EMPTY_VALUE;
    /** @var null константа значение не задано для ссылочных типов */
    const EMPTY_OBJECT = \Assay\Core\ICommon::EMPTY_OBJECT;
    /** @var array константа значение не задано для массивов */
    const EMPTY_ARRAY = \Assay\Core\ICommon::EMPTY_ARRAY;
    /** @var array константа индекс не определён */
    const NO_INDEX = -1;
    /** @var array Первый индекс массива */
    const FIRST_INDEX = \Assay\Core\ICommon::FIRST_INDEX;

    /** @var string разделитель элементов перечисления */
    const ENUMERATION_SEPARATOR = ",";
    /** @var string разделитель элементов перечисления */
    const WILDCARD_SYMBOL = "%";
    
    const FOREIGN_KEY_COLUMN = 'COLUMN';
    const FOREIGN_KEY_VALUE = 'VALUE';

    const MINIMUM = 'GREATER_OR_EQUAL';
    const MAXIMUM = 'LESS_OR_EQUAL';
    const EQUAL = 'EQUAL';

    const DATA_TYPE = 'CONVERT_TO_TYPE';
    const INTEGER_TYPE = 'INTEGER';
    const STRING_TYPE = 'STRING';

    const BETWEEN = 'BETWEEN';
    const INTEGER_EQUAL = '=';
    const STRING_EQUAL = 'LIKE';


    /** Сделать массив из параметров внешнего ключа
     * @param string $column колонка внешнего ключа
     * @param string $value значение внешнего ключа
     * @return array массив из параметров внешнего ключа
     */
    public static function setForeignKeyParameter(string $column, string $value):array;

    /** Извлечь колонку из массива параметров вненего ключа
     * @param array $foreignKey параметры внешнего ключа
     * @return string имя колонки
     */
    public static function getForeignKeyColumn(array $foreignKey):string;
    /** Извлечь значение из массива параметров вненего ключа
     * @param array $foreignKey параметры внешнего ключа
     * @return string имя колонки
     */
    public static function getForeignKeyValue(array $foreignKey):string;
    /** Получить строку целочисленных значения внешнего ключа
     * @param array $foreignKeys
     * @return string
     */
    public static function getIntegerValue(array $foreignKeys):string;
    /** Сформировать строку с именами колонок внешнего ключа
     * @param array $foreignKeys
     * @return string
     */
    public static function getNameString(array $foreignKeys):string;

    /** Получить условие для SQL запроса
     * @param array $searchPattern параметры выбора
     * @return string строка для условия выбора
     */
    public static function getSqlCondition( array $searchPattern):string;
}
