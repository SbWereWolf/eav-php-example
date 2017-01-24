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
                . ' , '
                . self::CODE
                . ' , '
                . self::NAME
                . ' , '
                . self::DESCRIPTION
                . ' , '
                . self::IS_HIDDEN
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

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            $result = false;
            if ($record != array()) {
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
            $result = array();

            $result [self::CODE] = $this->code;
            $result [self::DESCRIPTION] = $this->description;
            $result [self::ID] = $this->id;
            $result [self::IS_HIDDEN] = $this->isHidden;
            $result [self::NAME] = $this->name;

            return $result;
        }
    }
}
