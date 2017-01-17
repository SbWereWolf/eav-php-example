<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:09
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core;
    use Assay\Core\ICommon;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    /**
     * Древовидная структура
     */
    class Structure implements IStructure,
        \Assay\Core\IHide,
        \Assay\Core\INamedEntity,
        Core\IMutableEntity,
        Core\IReadableEntity
    {
        /** @var string константа для не пустого значения */
        const EMPTY_VALUE = ICommon::EMPTY_VALUE;
        /** @var string колонка идентификатора */
        const ID = 'id';

        /** @var string идентификатор записи таблицы */
        public $id = self::EMPTY_VALUE;
        /** @var string признак "является скрытым" */
        public $isHidden = Core\IHide::DEFAULT_IS_HIDDEN;
        /** @var string дата добавления записи */
        public $insertDate = self::EMPTY_VALUE;
        /** @var string родительский элемент */
        public $parent = self::EMPTY_VALUE;
        /** @var string код записи */
        public $code = self::EMPTY_VALUE;
        /** @var string наименование */
        public $name = self::EMPTY_VALUE;
        /** @var string описание */
        public $description = self::EMPTY_VALUE;

        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return bool значения колонок
         */
        public function readEntity(string $id):bool
        {
            $oneParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::QUERY_VALUE] = intval($id);
            $oneParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . self::ID
                . ' , '
                . self::PARENT
                . ' , '
                . self::CODE
                . ' , '
                . self::NAME
                . ' , '
                . self::DESCRIPTION
                . ' , '
                . self::IS_HIDDEN
                . ' , '
                . self::INSERT_DATE
                . ' FROM '
                . self::TABLE_NAME
                . ' WHERE '
                . self::ID
                . ' = '
                . $oneParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . '
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);

            $response = $sqlReader->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            return $isSuccessfulRead;
        }

        /** Прочитать данные экземпляра из БД
         * @return bool колонки
         */
        public function getStored():bool
        {
            $result = $this->readEntity($this->id);
            return $result;
        }

        /** Установить свойства экземпляра в соответствии с массивом
         * @param array $namedValue массив значений
         */
        public function setByNamedValue(array $namedValue)
        {
            $this->code = Core\Common::setIfExists(self::CODE, $namedValue, Core\Common::EMPTY_VALUE);
            $this->description = Core\Common::setIfExists(self::DESCRIPTION, $namedValue, Core\Common::EMPTY_VALUE);
            $this->id = Core\Common::setIfExists(self::ID, $namedValue, Core\Common::EMPTY_VALUE);
            $this->insertDate = Core\Common::setIfExists(self::INSERT_DATE, $namedValue, Core\Common::EMPTY_VALUE);
            $this->isHidden = Core\Common::setIfExists(self::IS_HIDDEN, $namedValue, Core\Common::EMPTY_VALUE);
            $this->name = Core\Common::setIfExists(self::NAME, $namedValue, Core\Common::EMPTY_VALUE);
            $this->parent = Core\Common::setIfExists(self::PARENT, $namedValue, Core\Common::EMPTY_VALUE);
        }

        /** Добавить запись в БД на основе экземпляра
         * @param array $namedValue значения колонок
         * @return bool успех выполнения
         */
        public function addReadable(array $namedValue):bool
        {
            $linkToParent = Core\Common::setIfExists(
                self::TABLE_NAME,
                $namedValue,
                Core\Common::EMPTY_VALUE);
            $parent = Core\Common::EMPTY_VALUE;
            if ($linkToParent != Core\Common::EMPTY_VALUE) {
                $parent = Core\Common::setIfExists(
                    IStructure::PARENT,
                    $linkToParent,
                    Core\Common::EMPTY_VALUE);
            }
            if ($parent != Core\Common::EMPTY_VALUE) {
                $parent = intval($parent);
            }
            if ($parent == 0) {
                $parent = null;
            }

            $oneParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':PARENT';
            $oneParameter[ISqlHandler::QUERY_VALUE] = $parent;
            $oneParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'INSERT INTO '
                . IStructure::TABLE_NAME
                . ' (' . self::PARENT . ')'
                . ' VALUES('
                . $oneParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . ')
                RETURNING ' . Structure::ID
                . ' , '
                . self::IS_HIDDEN
                . ' , '
                . self::INSERT_DATE
                . ' , '
                . self::PARENT
                . ' , '
                . self::CODE
                . ' , '
                . self::NAME
                . ' , '
                . self::DESCRIPTION

                . '
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRequest = SqlHandler::isNoError($response);
            if ($isSuccessfulRequest) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            return $isSuccessfulRequest;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool
        {
            $result = false;

            $stored = new Structure();
            $wasReadStored = $stored->readEntity($this->id);

            $storedEntity = array();
            $entity = array();
            if ($wasReadStored) {
                $storedEntity = $stored->toEntity();
                $entity = $this->toEntity();
            }

            $isContain = Core\Common::isOneArrayContainOther($entity, $storedEntity);
            if (!$isContain) {
                $result = $this->updateEntity();
            }

            return $result;
        }

        private function updateEntity():bool
        {

            $codeParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':CODE';
            $codeParameter[ISqlHandler::QUERY_VALUE] = $this->code;
            $codeParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;

            $descriptionParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':DESCRIPTION';
            $descriptionParameter[ISqlHandler::QUERY_VALUE] = $this->description;
            $descriptionParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;

            $idParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':ID';
            $idParameter[ISqlHandler::QUERY_VALUE] = intval($this->id);
            $idParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;

            $insertDateParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':INSERT_DATE';
            $insertDateParameter[ISqlHandler::QUERY_VALUE] = $this->insertDate;
            $insertDateParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;

            $isHiddenParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':IS_HIDDEN';
            $isHiddenParameter[ISqlHandler::QUERY_VALUE] = intval($this->isHidden);
            $isHiddenParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;

            $nameParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':NAME';
            $nameParameter[ISqlHandler::QUERY_VALUE] = $this->name;
            $nameParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;

            $parentParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':PARENT';
            $parentParameter[ISqlHandler::QUERY_VALUE] = intval($this->parent);
            $parentParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'UPDATE '
                . self::TABLE_NAME
                . ' SET'
                . self::CODE
                . ' = '
                . $codeParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . ' , '
                . self::IS_HIDDEN
                . ' = '
                . $isHiddenParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . ' , '
                . self::INSERT_DATE
                . ' = '
                . $insertDateParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . ' , '
                . self::PARENT
                . ' = '
                . $parentParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . ' , '
                . self::NAME
                . ' = '
                . $nameParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . ' , '
                . self::DESCRIPTION
                . ' = '
                . $descriptionParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . ' WHERE '
                . self::ID
                . ' = '
                . $idParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . '
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $insertDateParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $nameParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $parentParameter;

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRequest = SqlHandler::isNoError($response);
            return $isSuccessfulRequest;
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
            $result [self::INSERT_DATE] = $this->insertDate;
            $result [self::IS_HIDDEN] = $this->isHidden;
            $result [self::NAME] = $this->name;
            $result [self::PARENT] = $this->parent;

            return $result;
        }

        /** Чтение записи из БД по коду
         * @param string $code код записи
         * @return bool значения записи
         */
        public function loadByCode(string $code):bool
        {
            $oneParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':CODE';
            $oneParameter[ISqlHandler::QUERY_VALUE] = strval($code);
            $oneParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . self::ID
                . ' , '
                . self::PARENT
                . ' , '
                . self::CODE
                . ' , '
                . self::NAME
                . ' , '
                . self::DESCRIPTION
                . ' , '
                . self::IS_HIDDEN
                . ' , '
                . self::INSERT_DATE
                . ' FROM '
                . self::TABLE_NAME
                . ' WHERE '
                . self::CODE
                . ' = '
                . $oneParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . '
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

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
        public function getElementDescription(string $code = Core\INamedEntity::CODE,
                                              string $name = Core\INamedEntity::NAME,
                                              string $description = Core\INamedEntity::DESCRIPTION):array
        {
            $result[$code] = $this->code;
            $result[$name] = $this->name;
            $result[$description] = $this->description;
            return $result;
        }

        /** Скрыть сущность
         * @return bool успех операции
         */
        public function hideEntity():bool
        {
            $arguments[ISqlHandler::QUERY_TEXT] =
                'UPDATE '
                . IStructure::TABLE_NAME
                . ' SET ' . Core\IHide::IS_HIDDEN . ' ='
                . Core\IHide::DEFINE_AS_HIDDEN
                . ' WHERE '
                . self::ID . ' = '
                . $this->id
                . ';';

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);
            $result = SqlHandler::isNoError($response);

            if ($result) {
                $this->isHidden = true;
            }
            return $result;
        }

        /** Добавить дочерний элемент
         * @return string идентификатор добавленого элемента
         */
        public function addChild():string
        {
            $result = self::EMPTY_VALUE;

            $child = new Structure();

            $parentStructure[IStructure::TABLE_NAME][IStructure::PARENT] = $this->id;
            $isSuccess = $child->addReadable($parentStructure);
            if ($isSuccess) {
                $result = $child->id;
            }
            return $result;
        }

        /** Получить имена дочерних элементов
         * @param string $nameKey имя индекса для имени дочернего элемента структуры
         * @return array имена элементов
         */
        public function getChildrenNames(string $nameKey = 'CHILD_NAME'):array
        {
            $oneParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::QUERY_VALUE] = intval($this->id);
            $oneParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT 
                NAME AS '
                . $nameKey
                . 'FROM '
                . self::TABLE_NAME
                . ' WHERE '
                . self::PARENT . ' = '
                . $oneParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER] = $oneParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $resultCountChildren = SqlHandler::isNoError($response);

            $result = array();
            if ($resultCountChildren) {
                $result = SqlHandler::getAllRecords($response);
            }
            return $result;
        }

        /** Получить идентификатор ролительского элемнта
         * @return string идентификатор
         */
        public function getParent():string
        {
            $result = $this->parent;
            return $result;
        }

        /** Проверить что является разделом
         * @return bool успех проверки
         */
        public function isPartition():bool
        {
            $childrenCount = $this->calculateChildren();

            $result = $childrenCount > 0;

            return $result;
        }

        /** Проверить что является рубрикой
         * @return bool успех проверки
         */
        public function isRubric():bool
        {
            $childrenCount = $this->calculateChildren();

            $result = $childrenCount == 0;

            return $result;
        }

        /** Получить путь от этого элемента до корневого
         * @return array элменты пути
         */
        public function getPath():array
        {

        }

        /** Получить описание всех элементов
         * @return array элменты пути
         */
        public function getMap():array
        {

        }

        /** Выполнить поиск
         * @param string $searchString поисковая строка
         * @param string $structureCode код корневого элемента
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public function search(string $searchString = ICommon::EMPTY_VALUE, string $structureCode = ICommon::EMPTY_VALUE, int $start, int $paging):array
        {

        }


        /**
         * @return int
         */
        private function calculateChildren():int
        {
            $resultColumnName = 'RESULT';

            $oneParameter[ISqlHandler::QUERY_PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::QUERY_VALUE] = intval($this->id);
            $oneParameter[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT 
                SUM(1) AS '
                . $resultColumnName
                . 'FROM '
                . self::TABLE_NAME
                . ' WHERE '
                . self::PARENT . ' = '
                . $oneParameter[ISqlHandler::QUERY_PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER] = $oneParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $resultCountChildren = SqlHandler::isNoError($response);

            $result = -1;
            if ($resultCountChildren) {
                $record = SqlHandler::getFirstRecord($response);
                $result = intval($record[$resultColumnName]);
            }
            return $result;
        }


    }
}
