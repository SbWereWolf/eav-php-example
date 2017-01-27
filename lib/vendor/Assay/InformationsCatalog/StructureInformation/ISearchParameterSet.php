<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.01.2017
 * Time: 22:13
 */

namespace Assay\InformationsCatalog\StructureInformation;

use Assay\DataAccess;

interface ISearchParameterSet
{
    /** @var string константа значение не задано для значимых типов */
    const EMPTY_VALUE = \Assay\Core\ICommon::EMPTY_VALUE;
    /** @var array константа значение не задано для массивов */
    const EMPTY_ARRAY = \Assay\Core\ICommon::EMPTY_ARRAY;

    const INTEGER_TYPE = 'INTEGER';
    const STRING_TYPE = 'STRING';

    const BETWEEN = 'BETWEEN';
    const EQUAL = 'EQUAL';
    /** Получить условие для SQL запроса
     * @return string строка с условиями выбора для SQL запроса
     */
    public function getSqlCondition():string;

    /** Добавить параметр поиска
     * @param string $dataType Тип данных
     * @param string $first первый аргутмент
     * @param string $second второй аргумент
     * @return bool успех выполнения
     */
    public function addParameter(string $first,
                                 string $dataType = DataAccess\ICommon::STRING_TYPE,
                                 string $second = DataAccess\ICommon::EMPTY_VALUE):bool;
}
