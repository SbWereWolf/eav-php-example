<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:00
 */
namespace Assay\Core {

    use Assay\DataAccess\SqlReader;

    /**
     * реализация интерфейса для работы с именнуемыми сущностями
     */
    class Entity implements IEntity
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
            return $result;
        }

        public function hideEntity():bool
        {
            $result = true;
            $sqlReader = new SqlReader();
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
                    ".self::IS_HIDDEN."=".$ishidden[SqlReader::QUERY_PLACEHOLDER]."
                WHERE 
                    ".self::ID."=".$id[SqlReader::QUERY_PLACEHOLDER]."
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [$id,$ishidden];
            $result_sql = $sqlReader ->performQuery($arguments);
            $result = ($result_sql[SqlReader::ERROR_INFO][0] == Common::NO_ERROR)?true:$result;
            return $result;
        }
    }
}
