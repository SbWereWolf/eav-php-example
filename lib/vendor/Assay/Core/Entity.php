<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:00
 */
namespace Assay\Core {

    use Assay\DataAccess\SqlHandler;
    use Assay\DataAccess\SqlReader;

    /**
     * реализация интерфейса для работы с именнуемыми сущностями
     */
    class Entity implements IEntity , IHide
    {
        /** @var string имя таблицы БД для хранения сущности */
        //const TABLE_NAME = 'entity_table';
        /** @var string идентификатор записи таблицы */
        public $id;
        /** @var string признак "является скрытым" */
        public $isHidden;
        /** @var string дата добавления записи */
        public $insertDate;
        /** @var string дата добавления записи */
        protected $tablename = 'entity_table';

        public function addEntity():string
        {
            $result = 0;
            $arguments[SqlReader::QUERY_TEXT] = "
                INSERT INTO 
                    ".$this->tablename." 
                DEFAULT VALUES
                RETURNING ".self::ID.";
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [];

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $result = $record[self::ID];
            }
            return $result;
        }

        public function hideEntity():bool
        {
            $result = true;
            $id[SqlReader::QUERY_PLACEHOLDER] = ':ID';
            $id[SqlReader::QUERY_VALUE] = $this->id;
            $id[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $ishidden[SqlReader::QUERY_PLACEHOLDER] = ':IS_HIDDEN';
            $ishidden[SqlReader::QUERY_VALUE] = $this->isHidden;
            $ishidden[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $arguments[SqlReader::QUERY_TEXT] = "
                UPDATE 
                    ".$this->tablename."
                SET 
                    ".self::IS_HIDDEN."=".$ishidden[SqlReader::QUERY_PLACEHOLDER].",".self::ACTIVITY_DATE."=now()
                WHERE 
                    ".self::ID."=".$id[SqlReader::QUERY_PLACEHOLDER]."
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [$id,$ishidden];
            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $result = SqlHandler::isNoError($response);
            return $result;
        }
    }
}
