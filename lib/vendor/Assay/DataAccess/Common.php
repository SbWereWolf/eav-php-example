<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19.01.2017
 * Time: 20:37
 */

namespace Assay\DataAccess;


class Common implements ICommon
{
    
    /** Сделать массив из параметров внешнего ключа
     * @param string $column колонка внешнего ключа
     * @param string $value значение внешнего ключа
     * @return array массив из параметров внешнего ключа
     */
    public static function setForeignKeyParameter(string $column, string $value):array
    {
        $result = array(self::FOREIGN_KEY_COLUMN => $column, self::FOREIGN_KEY_VALUE => $value);
        return $result;
    }

    /** Извлечь колонку из массива параметров внешнего ключа
     * @param array $foreignKey параметры внешнего ключа
     * @return string имя колонки
     */
    public static function getForeignKeyColumn(array $foreignKey):string
    {
        $result = \Assay\Core\Common::setIfExists(self::FOREIGN_KEY_COLUMN, $foreignKey, self::EMPTY_VALUE);
        return $result;

    }

    /** Извлечь значение из массива параметров внешнего ключа
     * @param array $foreignKey параметры внешнего ключа
     * @return string имя колонки
     */
    public static function getForeignKeyValue(array $foreignKey):string
    {

        $result = \Assay\Core\Common::setIfExists(self::FOREIGN_KEY_VALUE, $foreignKey, self::EMPTY_VALUE);
        return $result;
    }

    /** Сформировать строку с именами колонок внешнего ключа
     * @param array $foreignKeys
     * @return string
     */
    public static function getNameString(array $foreignKeys):string
    {
        $columnNames = self::EMPTY_VALUE;
        foreach ($foreignKeys as $foreignKey) {
            $column = Common::getForeignKeyColumn($foreignKey);
            $mayAddKey = ($column != Common::EMPTY_VALUE);
            if ($mayAddKey) {
                $columnNames = self::ENUMERATION_SEPARATOR . $column;
            }
        }
        $columnNames = ltrim($columnNames, self::ENUMERATION_SEPARATOR);
        $columnNames = trim($columnNames);

        return $columnNames;
    }

    /** Получить строку целочисленных значения внешнего ключа
     * @param array $foreignKeys
     * @return string
     */
    public static function getIntegerValue(array $foreignKeys):string
    {
        $columnValues = self::EMPTY_VALUE;
        foreach ($foreignKeys as $foreignKey) {
            $value = intval(Common::getForeignKeyValue($foreignKey));
            $mayAddKey = ($value > 0);
            if ($mayAddKey) {
                $columnValues = self::ENUMERATION_SEPARATOR . $value;
            }
        }
        $columnValues = ltrim($columnValues, self::ENUMERATION_SEPARATOR);
        $columnValues = trim($columnValues);

        return $columnValues;
    }

    public static function getColumnValuePairByUnionString(array $foreignKeys, string $unionString):string
    {
        $conditionString = self::EMPTY_VALUE;
        foreach ($foreignKeys as $foreignKey) {
            $column = Common::getForeignKeyColumn($foreignKey);
            $value = intval(Common::getForeignKeyValue($foreignKey));

            $maySetCondition = ($column != Common::EMPTY_VALUE) && ($value > 0);
            if ($maySetCondition) {
                $conditionString = $unionString . " $column = $value ";
            }
        }
        $conditionString = preg_replace('/' . $unionString . '/', '', $conditionString, 1);
        
        return $conditionString;
    }
    
    /** Получить условие для SQL запроса
     * @param array $searchPattern параметры выбора
     * @return string строка для условия выбора
     */
    public static function getSqlCondition( array $searchPattern):string
    {
        $isSingle = count($searchPattern) == 1;
        $isMany = count($searchPattern) > 1;

        $conditionString = self::EMPTY_VALUE;

        if ($isSingle) {
            $singlePattern = $searchPattern[self::FIRST_INDEX];

            $conditionString = self::setConditionBySinglePattern($singlePattern);
        }
        if (!$isMany) {
            $conditionString = self::setConditionByManyPatterns($searchPattern);
        }

        return $conditionString;

    }

    private static function setConditionBySinglePattern($singlePattern):string
    {
        $dataType = \Assay\Core\Common::setIfExists(self::DATA_TYPE, $singlePattern, self::EMPTY_VALUE);
        $operator = self::INTEGER_EQUAL;
        $setAddon = false;
        switch ($dataType) {
            case self::INTEGER_TYPE :
                $operator = self::INTEGER_EQUAL;
                break;
            case self::STRING_TYPE:
                $operator = self::STRING_EQUAL;
                $setAddon = true;
                break;
        }

        $isBetween = array_key_exists(self::MINIMUM, $singlePattern)
            && array_key_exists(self::MAXIMUM, $singlePattern);
        if ($isBetween) {
            $operator = self::BETWEEN;
        }

        $placeholder = \Assay\Core\Common::setIfExists(self::EQUAL, $singlePattern, self::EMPTY_VALUE);

        if ($placeholder == self::EMPTY_VALUE) {
            $placeholder = \Assay\Core\Common::setIfExists(self::MINIMUM, $singlePattern, self::EMPTY_VALUE);

        }
        $isPlaceholderSet = $placeholder != self::EMPTY_VALUE;

        if ($setAddon && $isPlaceholderSet) {
            $patternAddon = ICommon::WILDCARD_SYMBOL;
            $placeholder = " '$patternAddon' || $placeholder || '$patternAddon'";
        }

        $searchString = self::EMPTY_VALUE;
        $isSearchSet = false;
        if ($isPlaceholderSet) {
            $searchString = " $operator $placeholder ";
            $isSearchSet = true;
        }

        if ($isBetween && $isSearchSet) {
            $additionalPlaceholder = \Assay\Core\Common::setIfExists(self::MAXIMUM, $singlePattern, self::EMPTY_ARRAY);
            $searchString .= " AND $additionalPlaceholder ";
        }

        return $searchString;
    }


    private static function setConditionByManyPatterns($searchPattern)
    {
        $conditionString = self::EMPTY_VALUE;

        foreach ($searchPattern as $pattern) {

            $single = \Assay\Core\Common::setIfExists(self::EQUAL, $pattern, self::EMPTY_ARRAY);
            $placeholder = self::EMPTY_VALUE;
            if ($single != self::EMPTY_ARRAY) {
                $placeholder = \Assay\Core\Common::setIfExists(ISqlHandler::PLACEHOLDER, $single, self::EMPTY_VALUE);
            }
            if ($placeholder != self::EMPTY_VALUE) {
                $conditionString .= ICommon::ENUMERATION_SEPARATOR . $placeholder;
            }
        }
        if($conditionString!=self::EMPTY_VALUE ){
            $conditionString = 'IN (' . ltrim($conditionString, ICommon::ENUMERATION_SEPARATOR) . ')';
        }

        return $conditionString;
    }
}
