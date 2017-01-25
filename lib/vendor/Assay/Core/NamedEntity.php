<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:03
 */
namespace Assay\Core {

    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    /**
     * Реализация интерфейса для работы с именнуемыми сущностями
     */
    class NamedEntity extends Entity implements INamedEntity
    {
        /** @var string константа значение не задано для значимых типов */
        const EMPTY_VALUE = ICommon::EMPTY_VALUE;
        /** @var null константа значение не задано для ссылочных типов */
        const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
        /** @var array константа значение не задано для массивов */
        const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

        /** @var string код */
        public $code = self::EMPTY_VALUE;
        /** @var string имя */
        public $name = self::EMPTY_VALUE;
        /** @var string описание */
        public $description = self::EMPTY_VALUE;

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

        /** Получить имя и описание записи
         * @param string $code значение ключа для свойства код
         * @param string $name значение ключа для свойства имя
         * @param string $description значение ключа для свойства описание
         * @return array массив с именем и описанием
         */
        public function getElementDescription(string $code = INamedEntity::CODE,
                                              string $name = INamedEntity::NAME,
                                              string $description = INamedEntity::DESCRIPTION):array
        {
            $result[$code] = $this->code;
            $result[$name] = $this->name;
            $result[$description] = $this->description;
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
                . ' FROM '
                . $this->tablename
                . ' WHERE '
                . self::ID
                . ' = '
                . $oneParameter[ISqlHandler::PLACEHOLDER]
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
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

        public function setByNamedValue(array $namedValue):bool
        {
            $this->code = Common::setIfExists(self::CODE, $namedValue, self::EMPTY_VALUE);
            $this->description = Common::setIfExists(self::DESCRIPTION, $namedValue, self::EMPTY_VALUE);
            $this->id = Common::setIfExists(self::ID, $namedValue, self::EMPTY_VALUE);
            $this->isHidden = Common::setIfExists(self::IS_HIDDEN, $namedValue, self::EMPTY_VALUE);
            $this->name = Common::setIfExists(self::NAME, $namedValue, self::EMPTY_VALUE);

            return true;
        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {
            $result = self::EMPTY_ARRAY;

            $result [self::CODE] = $this->code;
            $result [self::DESCRIPTION] = $this->description;
            $result [self::ID] = $this->id;
            $result [self::IS_HIDDEN] = $this->isHidden;
            $result [self::NAME] = $this->name;

            return $result;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успех выполнения
         */
        public function mutateEntity():bool
        {
            $result = false;

            $stored = new NamedEntity();
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

            $arguments[ISqlHandler::QUERY_TEXT] =
                'UPDATE '
                . $this->tablename
                . ' SET '
                . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::NAME . ' = ' . $nameParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::DESCRIPTION . ' = ' . $descriptionParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' RETURNING '
                . self::ID
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $nameParameter;

            $sqlWriter = new SqlHandler(ISqlHandler::DATA_WRITER);
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
