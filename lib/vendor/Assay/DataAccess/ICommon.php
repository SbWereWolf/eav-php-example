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
    const FOREIGN_KEY_COLUMN = 'COLUMN';
    const FOREIGN_KEY_VALUE = 'VALUE';

    /** Сделать массив из параметров внешнего ключа
     * @param string $column колонка внешнего ключа
     * @param string $value значение внешнего ключа
     * @return array массив из параметров внешнего ключа
     */
    public function setForeignKeyParameter(string $column, string $value):array;

    /** Извлечь колонку из массива параметров вненего ключа
     * @param array $foreignKey параметры внешнего ключа
     * @return string имя колонки
     */
    public function getForeignKeyColumn(array $foreignKey):string;
    /** Извлечь значение из массива параметров вненего ключа
     * @param array $foreignKey параметры внешнего ключа
     * @return string имя колонки
     */
    public function getForeignKeyValue(array $foreignKey):string;
}
