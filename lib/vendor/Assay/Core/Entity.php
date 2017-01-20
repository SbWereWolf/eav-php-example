<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:00
 */
namespace Assay\Core {

    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    /**
     * реализация интерфейса для работы с именнуемыми сущностями
     */
    class Entity implements IEntity
    {
        const TABLE_NAME = 'entity_table';
        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;
        /** @var string идентификатор записи таблицы */
        public $id;
        /** @var string признак "является скрытым" */
        public $isHidden;

        public function addEntity():bool
        {
            $arguments[ISqlHandler::QUERY_TEXT] = '
            INSERT INTO ' . $this->tablename
                . ' DEFAULT VALUES RETURNING '
                . self::ID
                .' , '.self::IS_HIDDEN 
                .' ; '
                ;

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);
            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
            }

            $this->id = Common::setIfExists(self::ID, $record, self::EMPTY_VALUE);
            $this->isHidden = Common::setIfExists(self::IS_HIDDEN, $record, self::EMPTY_VALUE);

            $result = $this->id != self::EMPTY_VALUE &&  $this->isHidden  != self::EMPTY_VALUE;

            return $result;
        }

        public function hideEntity():bool
        {
            $id[ISqlHandler::PLACEHOLDER] = ':ID';
            $id[ISqlHandler::VALUE] = intval($this->id);
            $id[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $isHidden[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $isHidden[ISqlHandler::VALUE] = intval(self::DEFINE_AS_HIDDEN);
            $isHidden[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[SqlHandler::QUERY_TEXT] = '
            UPDATE ' . $this->tablename . '
            SET ' . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER] .
                ' WHERE ' . self::ID . ' = ' . $id[ISqlHandler::PLACEHOLDER]
            .' RETURNING '.self::ID .' , '.self::IS_HIDDEN .' ; '
            ;

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $id;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHidden;

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);
            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
            }

            $this->id = Common::setIfExists(self::ID, $record, self::EMPTY_VALUE);
            $this->isHidden = Common::setIfExists(self::IS_HIDDEN, $record, self::EMPTY_VALUE);

            $result = $this->id != self::EMPTY_VALUE &&  $this->isHidden  != self::EMPTY_VALUE;

            return $result;
        }
        /** Загрузить данные в соответствии с идентификатором
         * @param string $id идентификатор записи
         * @return bool успех выполнения
         */
        public function loadById(string $id):bool{
            $result = false;
            return $result;
        }

        /** Загрузить данные сохранённые в БД
         * @return bool успех выполнения
         */
        public function getStored():bool{
            $result = false;
            return $result;
        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array{
            $result =array();
            return $result;
        }
        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool{
            $result = false;
            return $result;
        }

        /** Установить свойства экземпляра в соответствии со значениями
         * @param array $namedValue массив значений
         * @return bool успех выполнения
         */
        public function setByNamedValue(array $namedValue):bool{
            $result = false;
            return $result;
        }
    }
}
