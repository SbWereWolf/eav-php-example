<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:01
 */
namespace Assay\Core {

    use Assay\DataAccess\Common;
    use Assay\DataAccess\ICommon;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    /**
     * Реализация интерфейса работы со связями сущностей
     */
    class LinkageEntity extends Record implements ILinkageEntity
    {
        /** @var string строка соединитель элементов */
        const UNION_BY_AND = " AND ";
        
        /** @var string константа значение не задано для значимых типов */
        const EMPTY_VALUE = ICommon::EMPTY_VALUE;
        /** @var null константа значение не задано для ссылочных типов */
        const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
        /** @var array константа значение не задано для массивов */
        const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'linkage_entity';

        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;

        /** Удалить связь
         * @param array $foreignKeys значение внешнего ключа
         * @return bool значения колонок
         */
        public function dropLinkage(array $foreignKeys):bool
        {
            $conditionString = Common::getColumnValuePairByUnionString($foreignKeys,self::UNION_BY_AND);

            $arguments[ISqlHandler::QUERY_TEXT] =
                " DELETE FROM $this->tablename WHERE $conditionString"
                . ' ; ';

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulDelete = SqlHandler::isNoError($response);
            
            $deleteCount = 0 ;
            if ($isSuccessfulDelete) {
                $records = SqlHandler::getAllRecords($response);
                $deleteCount = count($records); 
            }

            $result = $deleteCount  > 0 ;
            return $result;
        }

        /** Добавить запись в БД на основе экземпляра
         * @param array $foreignKeys значение внешнего ключа
         * @return bool успех выполнения
         */
        public function addLinkage(array $foreignKeys):bool
        {
            $columnNames = Common::getNameString($foreignKeys);
            $columnValues = Common::getIntegerValue($foreignKeys);
            
            $columnNamesInsideParentheses = "($columnNames)";
            $columnValuesInsideParentheses = "($columnValues)";

            $arguments[ISqlHandler::QUERY_TEXT] =
                '
INSERT INTO ' . "$this->tablename $columnNamesInsideParentheses 
VALUES $columnValuesInsideParentheses 
 RETURNING $columnNames" . ICommon::ENUMERATION_SEPARATOR . self::ID
                . ' ; ';

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);
            $record = self::EMPTY_ARRAY;
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            $result = $record != self::EMPTY_ARRAY;
            return $result;
        }

        public function setByNamedValue(array $namedValue):bool
        {
            $this->id = \Assay\Core\Common::setIfExists(self::ID, $namedValue, self::EMPTY_VALUE);

            return true;
        }
    }
}
