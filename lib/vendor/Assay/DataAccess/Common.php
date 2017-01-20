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
    public function setForeignKeyParameter(string $column, string $value):array{
        $result = array( self::FOREIGN_KEY_COLUMN=>$column,self::FOREIGN_KEY_VALUE=> $value);
        return $result;
    }

    /** Извлечь колонку из массива параметров вненего ключа
     * @param array $foreignKey параметры внешнего ключа
     * @return string имя колонки
     */
    public function getForeignKeyColumn(array $foreignKey):string{
        $result = \Assay\Core\Common::setIfExists(self::FOREIGN_KEY_COLUMN, $foreignKey, \Assay\Core\Common::EMPTY_OBJECT);
        return $result;
        
    }
    /** Извлечь значение из массива параметров вненего ключа
     * @param array $foreignKey параметры внешнего ключа
     * @return string имя колонки
     */
    public function getForeignKeyValue(array $foreignKey):string{

        $result = \Assay\Core\Common::setIfExists(self::FOREIGN_KEY_VALUE, $foreignKey, \Assay\Core\Common::EMPTY_OBJECT);
        return $result;
    }
}
