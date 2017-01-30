<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.01.2017
 * Time: 16:11
 */

namespace Assay\InformationsCatalog\StructureInformation;

use Assay\DataAccess;

class SearchParameterSet implements ISearchParameterSet
{
    public $pattern = self::EMPTY_ARRAY;


    /** Получить условие для SQL запроса
     * @return string строка с условиями выбора для SQL запроса
     */
    public function getSqlCondition():string
    {
        $searchPattern = $this->pattern;
        $conditionString = DataAccess\Common::getSqlCondition($searchPattern);

        return $conditionString;
    }

    /** Добавить параметр поиска
     * @param string $dataType Тип данных
     * @param string $first первый аргутмент
     * @param string $second второй аргумент
     * @return bool успех выполнения
     */
    public function addParameter(string $first,
                                 string $dataType = DataAccess\ICommon::STRING_TYPE,
                                 string $second = self::EMPTY_VALUE):bool
    {
        if ($second != self::EMPTY_VALUE) {
            $parameter []= [
                DataAccess\ICommon::MAXIMUM => $second,
                DataAccess\ICommon::MINIMUM => $first,
            ];
        }else{
            $parameter = [
                DataAccess\ICommon::EQUAL => $first,
                DataAccess\ICommon::DATA_TYPE => $dataType,
            ];
        }
        $this->pattern[] = $parameter;
        $result = true;

        return $result;
    }
}
