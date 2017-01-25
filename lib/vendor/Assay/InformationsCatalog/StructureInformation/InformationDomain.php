<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:12
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\Common;
    use Assay\Core\NamedEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    /**
     * Домен свойства
     */
    class InformationDomain extends NamedEntity implements IInformationDomain
    {
        /** @var string имя таблицы */
        protected $tablename = self::TABLE_NAME;

        /** @var string тип редактирования */
        public $typeEdit = self::EMPTY_VALUE;
        /** @var string тип поиска */
        public $searchType = self::EMPTY_VALUE;
        /** @var string тип данных */
        public $dataType = self::EMPTY_VALUE;

        /** Загрузить по коду записи
         * @param string $code код записи
         * @return bool успех выполнения
         */
        public function loadByCode(string $code):bool
        {
            $codeParameter[ISqlHandler::PLACEHOLDER] = ':CODE';
            $codeParameter[ISqlHandler::VALUE] = strval($code);
            $codeParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $isHiddenParameter[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $isHiddenParameter[ISqlHandler::VALUE] = intval(self::DEFINE_AS_NOT_HIDDEN);
            $isHiddenParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . self::ID
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::SEARCH_TYPE
                . ' , ' . self::TYPE_EDIT
                . ' , ' . self::DATA_TYPE
                . ' FROM '
                . $this->tablename
                . ' WHERE '
                . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' AND ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . '
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);

            $response = $sqlReader->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            return $isSuccessfulRead;
        }

        public function setByNamedValue(array $namedValue):bool
        {
            $result = parent::setByNamedValue($namedValue);

            $this->searchType = Common::setIfExists(self::SEARCH_TYPE, $namedValue, self::EMPTY_VALUE);
            $this->typeEdit = Common::setIfExists(self::TYPE_EDIT, $namedValue, self::EMPTY_VALUE);
            $this->dataType = Common::setIfExists(self::DATA_TYPE, $namedValue, self::EMPTY_VALUE);

            return $result;
        }

        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return bool успех выполнения
         */
        public function loadById(string $id):bool
        {
            $oneParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::VALUE] = intval($id);
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . self::ID
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::SEARCH_TYPE
                . ' , ' . self::TYPE_EDIT
                . ' , ' . self::DATA_TYPE
                . ' FROM '
                . $this->tablename
                . ' WHERE '
                . self::ID
                . ' = '
                . $oneParameter[ISqlHandler::PLACEHOLDER]
                . '
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $sqlReader = new SqlHandler(ISqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = self::EMPTY_ARRAY;
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            $result = false;
            if ($record != self::EMPTY_ARRAY) {
                $result = true;
            }

            return $result;
        }
        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {
            $result = parent::toEntity();

            $result [self::SEARCH_TYPE] = $this->searchType;
            $result [self::TYPE_EDIT] = $this->typeEdit;
            $result [self::DATA_TYPE] = $this->dataType;

            return $result;
        }
        /** Обновляет (изменяет) запись в БД
         * @return bool успех выполнения
         */
        public function mutateEntity():bool
        {
            $result = false;

            $stored = new InformationDomain();
            $wasReadStored = $stored->loadById($this->id);

            $storedEntity = array();
            $entity = array();
            if ($wasReadStored) {
                $storedEntity = $stored->toEntity();
                $entity = $this->toEntity();
            }

            $isContain = Common::isOneArrayContainOther($entity, $storedEntity);

            if (!$isContain) {
                $result = $this->updateEntity();
            }

            return $result;
        }

        /** Обновить данные в БД
         * @return bool успех выполнения
         */
        protected function updateEntity():bool
        {

            $codeParameter[ISqlHandler::PLACEHOLDER] = ':CODE';
            $codeParameter[ISqlHandler::VALUE] = $this->code;
            $codeParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $descriptionParameter[ISqlHandler::PLACEHOLDER] = ':DESCRIPTION';
            $descriptionParameter[ISqlHandler::VALUE] = $this->description;
            $descriptionParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $idParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $idParameter[ISqlHandler::VALUE] = intval($this->id);
            $idParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $isHiddenParameter[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $isHiddenParameter[ISqlHandler::VALUE] = intval($this->isHidden);
            $isHiddenParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $nameParameter[ISqlHandler::PLACEHOLDER] = ':NAME';
            $nameParameter[ISqlHandler::VALUE] = $this->name;
            $nameParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $searchTypeParameter[ISqlHandler::PLACEHOLDER] = ':SEARCH_TYPE';
            $searchTypeParameter[ISqlHandler::VALUE] = intval($this->searchType);
            $searchTypeParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $typeEditParameter[ISqlHandler::PLACEHOLDER] = ':TYPE_EDIT';
            $typeEditParameter[ISqlHandler::VALUE] = intval($this->typeEdit);
            $typeEditParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $dataTypeParameter[ISqlHandler::PLACEHOLDER] = ':DATA_TYPE';
            $dataTypeParameter[ISqlHandler::VALUE] = intval($this->dataType);
            $dataTypeParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'UPDATE '
                . $this->tablename
                . ' SET '
                . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::NAME . ' = ' . $nameParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::DESCRIPTION . ' = ' . $descriptionParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::TYPE_EDIT . ' = ' . $typeEditParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::SEARCH_TYPE . ' = ' . $searchTypeParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::DATA_TYPE . ' = ' . $searchTypeParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                .' RETURNING '
                . self::ID
                . ' , ' . self::CODE
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::TYPE_EDIT
                . ' , ' . self::SEARCH_TYPE
                . ' , ' . self::DATA_TYPE
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $nameParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $searchTypeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $typeEditParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $dataTypeParameter;


            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRequest = SqlHandler::isNoError($response);

            $record = self::EMPTY_ARRAY;
            if ($isSuccessfulRequest) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            $result = false;
            if ($record != self::EMPTY_ARRAY) {
                $result = true;
            }
            return $result;
        }
    }
}
