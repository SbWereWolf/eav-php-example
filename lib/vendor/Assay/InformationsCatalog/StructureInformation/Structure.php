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
    class Structure extends Core\NamedEntity implements IStructure

    {
        /** @var string константа для не пустого значения */
        const EMPTY_VALUE = ICommon::EMPTY_VALUE;

        /** @var string имя таблицы */
        const TABLE_NAME = 'structure';

        /** @var string колонка идентификатора */
        const ID = 'id';

        /** @var string идентификатор записи таблицы */
        public $id = self::EMPTY_VALUE;
        /** @var string признак "является скрытым" */
        public $isHidden = Core\IEntity::DEFAULT_IS_HIDDEN;
        /** @var string родительский элемент */
        public $parent = self::EMPTY_VALUE;
        /** @var string код записи */
        public $code = self::EMPTY_VALUE;
        /** @var string наименование */
        public $name = self::EMPTY_VALUE;
        /** @var string описание */
        public $description = self::EMPTY_VALUE;

        protected $tablename = self::TABLE_NAME;

        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return bool значения колонок
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
                . self::PARENT
                . ' , '
                . self::CODE
                . ' , '
                . self::NAME
                . ' , '
                . self::DESCRIPTION
                . ' , '
                . self::IS_HIDDEN
                . ' FROM '
                . self::TABLE_NAME
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

        /** Прочитать данные экземпляра из БД
         * @return bool колонки
         */
        public function getStored():bool
        {
            $result = $this->loadById($this->id);
            return $result;
        }

        /** Установить свойства экземпляра в соответствии со значениями
         * @param array $namedValue массив значений
         * @return bool успех выполнения
         */
        public function setByNamedValue(array $namedValue):bool
        {
            $this->code = Core\Common::setIfExists(self::CODE, $namedValue, Core\Common::EMPTY_VALUE);
            $this->description = Core\Common::setIfExists(self::DESCRIPTION, $namedValue, Core\Common::EMPTY_VALUE);
            $this->id = Core\Common::setIfExists(self::ID, $namedValue, Core\Common::EMPTY_VALUE);
            $this->isHidden = Core\Common::setIfExists(self::IS_HIDDEN, $namedValue, Core\Common::EMPTY_VALUE);
            $this->name = Core\Common::setIfExists(self::NAME, $namedValue, Core\Common::EMPTY_VALUE);
            $this->parent = Core\Common::setIfExists(self::PARENT, $namedValue, Core\Common::EMPTY_VALUE);

            return true;
        }

        /** Добавить запись в БД на основе экземпляра
         * @param array $parentKey значения колонок
         * @return bool успех выполнения
         */
        public function setParent(array $parentKey):bool
        {
            $parent = Core\Common::setIfExists(
                self::PARENT,
                $parentKey,
                Core\Common::EMPTY_VALUE);
            if ($parent != Core\Common::EMPTY_VALUE) {
                $parent = intval($parent);
            }
            if ($parent == 0) {
                $parent = null;
            }

            $parentParameter[ISqlHandler::PLACEHOLDER] = ':PARENT';
            $parentParameter[ISqlHandler::VALUE] = $parent;
            $parentParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $idParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $idParameter[ISqlHandler::VALUE] = intval($this->id);
            $idParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                '
UPDATE '
                . self::TABLE_NAME
                . ' SET ' . self::PARENT . ' = ' . $parentParameter[ISqlHandler::PLACEHOLDER]
                . '
 WHERE ' . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER] . '
 RETURNING ' . self::ID
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::PARENT
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . '
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $parentParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;

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
            $wasReadStored = $stored->loadById($this->id);

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

            $parentParameter[ISqlHandler::PLACEHOLDER] = ':PARENT';
            $parentParameter[ISqlHandler::VALUE] = intval($this->parent);
            $parentParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'UPDATE '
                . self::TABLE_NAME
                . ' SET '
                . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::PARENT . ' = ' . $parentParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::NAME . ' = ' . $nameParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::DESCRIPTION . ' = ' . $descriptionParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
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
            $codeParameter[ISqlHandler::PLACEHOLDER] = ':CODE';
            $codeParameter[ISqlHandler::VALUE] = strval($code);
            $codeParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $isHiddenParameter[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $isHiddenParameter[ISqlHandler::VALUE] = intval(self::DEFINE_AS_NOT_HIDDEN);
            $isHiddenParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . self::ID
                . ' , ' . self::PARENT
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::IS_HIDDEN
                . ' FROM '
                . self::TABLE_NAME
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
        /*
                public function hideEntity():bool
                {
                    $arguments[ISqlHandler::QUERY_TEXT] =
                        'UPDATE '
                        . self::TABLE_NAME
                        . ' SET ' . self::IS_HIDDEN . ' ='
                        . self::DEFINE_AS_HIDDEN
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
        */

        /** Добавить дочерний элемент
         * @return string идентификатор добавленого элемента
         */
        public function addChild():string
        {
            $result = self::EMPTY_VALUE;

            $child = new Structure();

            $child->addEntity();

            $parentStructure[IStructure::PARENT] = $this->id;
            $isSuccess = $child->setParent($parentStructure);
            if ($isSuccess) {
                $result = $child->id;
            }
            return $result;
        }

        /** Получить имена дочерних элементов
         * @param string $nameKey имя индекса для имени дочернего элемента структуры
         * @return array имена элементов
         */
        public function getChildrenNames(string $nameKey = Core\INamedEntity::NAME):array
        {
            $idParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $idParameter[ISqlHandler::VALUE] = intval($this->id);
            $idParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $isHiddenParameter[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $isHiddenParameter[ISqlHandler::VALUE] = intval(self::DEFINE_AS_NOT_HIDDEN);
            $isHiddenParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT 
                ' . self::NAME . ' AS '
                . $nameKey
                . ' FROM '
                . self::TABLE_NAME
                . ' WHERE '
                . self::PARENT . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $resultCountChildren = SqlHandler::isNoError($response);

            $result = array();
            if ($resultCountChildren) {
                $result = SqlHandler::getAllRecords($response);
            }
            return $result;
        }

        public function getChildrenCodes(string $codeKey = Core\INamedEntity::CODE):array
        {
            $idParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $idParameter[ISqlHandler::VALUE] = intval($this->id);
            $idParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $isHiddenParameter[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $isHiddenParameter[ISqlHandler::VALUE] = intval(self::DEFINE_AS_NOT_HIDDEN);
            $isHiddenParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT 
                ' . self::CODE . ' AS '
                . $codeKey
                . ' FROM '
                . self::TABLE_NAME
                . ' WHERE '
                . self::PARENT . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' ANd ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER] .
                ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

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

            $idParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $idParameter[ISqlHandler::VALUE] = intval($this->id);
            $idParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $queryText =
                '
WITH RECURSIVE children ( id, structure_id, code, name,level ) AS
(
  SELECT
    SC.' . self::ID . ',
    SC.' . self::PARENT . ',
    SC.' . self::CODE . ',
    SC.' . self::NAME . ',
    0
  FROM ' . self::TABLE_NAME . ' SC
  WHERE SC.id = ' . $idParameter[ISqlHandler::PLACEHOLDER] . '
  UNION
  SELECT
    SN.' . self::ID . ',
    SN.' . self::PARENT . ',
    SN.' . self::CODE . ',
    SN.' . self::NAME . ',
    level + 1
  FROM ' . self::TABLE_NAME . ' SN
    INNER JOIN children C
      ON (C.' . self::PARENT . ' = SN.' . self::ID . ')
)
SELECT '
                . self::CODE
                . ', '
                . self::NAME
                . '
FROM children
ORDER BY level DESC
;
';

            $arguments[ISqlHandler::QUERY_TEXT] = $queryText;

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);

            $response = $sqlReader->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $records = array();
            if ($isSuccessfulRead) {
                $records = SqlHandler::getAllRecords($response);
                $this->setByNamedValue($records);
            }

            return $records;
        }

        /**
         * @param string $code
         * @return array
         */
        public static function getMap(string $code = ' '):array
        {
            $codeParameter[ISqlHandler::PLACEHOLDER] = ':CODE';
            $codeParameter[ISqlHandler::VALUE] = $code;
            $codeParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $isHiddenParameter[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $isHiddenParameter[ISqlHandler::VALUE] = intval(self::DEFINE_AS_NOT_HIDDEN);
            $isHiddenParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                '
WITH RECURSIVE nodes ( id, structure_id, code, name, path, level ) AS
(
  SELECT
    SC.' . self::ID . ',
    SC.' . self::PARENT . ',
    SC.' . self::CODE . ',
    SC.' . self::NAME . ',
    CAST ( SC.' . self::CODE . ' AS text ),
    0
  FROM ' . self::TABLE_NAME . ' SC
  WHERE SC.' . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER] . ' 
    OR (
        ' . $codeParameter[ISqlHandler::PLACEHOLDER] . ' = \' \' 
        AND SC.' . self::PARENT . ' IS NULL
    )
  UNION
  SELECT
    SN.' . self::ID . ',
    SN.' . self::PARENT . ',
    SN.' . self::CODE . ',
    SN.' . self::NAME . ',
    CAST ( N.path || \'-\'|| SN.' . self::CODE . '  AS text ),
    level + 1
  FROM ' . self::TABLE_NAME . ' SN
    INNER JOIN nodes N
      ON (N.id = SN.' . self::PARENT . ')
  WHERE SN.'
                . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER] . '
)
SELECT
  ' . self::CODE . ',
  ' . self::NAME . ',
  path,
  level
FROM nodes
ORDER BY path
;
';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);

            $resultCountChildren = SqlHandler::isNoError($response);

            $record = array();
            if ($resultCountChildren) {
                $record = SqlHandler::getAllRecords($response);
            }
            return $record;
        }

        /** Выполнить поиск
         * @param string $searchString поисковая строка
         * @param string $structureCode код корневого элемента
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public static function search(string $searchString = ICommon::EMPTY_VALUE,
                                      string $structureCode = ICommon::EMPTY_VALUE,
                                      int $start = 0,
                                      int $paging = 0):array
        {
            $searchStringParameter[ISqlHandler::PLACEHOLDER] = ':SEARCH_STRING';
            $searchStringParameter[ISqlHandler::VALUE] = $searchString;
            $searchStringParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $structureCodeParameter[ISqlHandler::PLACEHOLDER] = ':CODE';
            $structureCodeParameter[ISqlHandler::VALUE] = $structureCode;
            $structureCodeParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $isHiddenParameter[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $isHiddenParameter[ISqlHandler::VALUE] = intval(self::DEFINE_AS_NOT_HIDDEN);
            $isHiddenParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $queryText =
                'SELECT '
                . self::CODE
                . ' , '
                . self::NAME
                . ' , '
                . self::DESCRIPTION
                . ' FROM '
                . self::TABLE_NAME
                . ' AS SO '
                . ' WHERE SO.'
                . self::IS_HIDDEN
                . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER];

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $queryWithString = '';
            if ($searchStringParameter[ISqlHandler::VALUE] != ICommon::EMPTY_VALUE) {

                $queryWithString =
                    ' AND ( SO.'
                    . self::NAME
                    . ' LIKE '
                    . '\'%\'||'
                    . $searchStringParameter[ISqlHandler::PLACEHOLDER]
                    . '||\'%\''
                    . ' OR SO.'
                    . self::DESCRIPTION
                    . ' LIKE '
                    . '\'%\'||'
                    . $searchStringParameter[ISqlHandler::PLACEHOLDER]
                    . '||\'%\' )';
                $arguments[ISqlHandler::QUERY_PARAMETER][] = $searchStringParameter;
            }
            $queryText .= $queryWithString;

            $queryWithCode = '';
            if ($structureCodeParameter[ISqlHandler::VALUE] != ICommon::EMPTY_VALUE) {

                $queryWithCode =

                    '
AND EXISTS
(
    SELECT NULL
    FROM
      (
        WITH RECURSIVE children ( id, structure_id, code, level ) AS
        (
          SELECT
            SC.' . self::ID . ',
            SC.' . self::PARENT . ',
            SC.' . self::CODE . ',
            0
          FROM ' . self::TABLE_NAME . ' SC
          WHERE SC.' . self::ID . ' = SO.' . self::ID . '
          UNION
          SELECT
            SN.' . self::ID . ',
            SN.' . self::PARENT . ',
            SN.' . self::CODE . ',
            level + 1
          FROM ' . self::TABLE_NAME . ' SN
            INNER JOIN children C
              ON (C.' . self::PARENT . ' = SN.' . self::ID . ')
        )
        SELECT code
        FROM children
        ORDER BY level DESC
        LIMIT 1
      ) AS R
    WHERE R.code = '
                    . $structureCodeParameter[ISqlHandler::PLACEHOLDER]
                    . ')';

                $arguments[ISqlHandler::QUERY_PARAMETER][] = $structureCodeParameter;
            }
            $queryText .= $queryWithCode;

            $queryLimit = '';
            if ($paging > 0) {
                $queryLimit = " LIMIT $paging ";
            }
            $queryText .= $queryLimit;

            $queryOffset = '';
            if ($start > 0) {
                $queryOffset = "  OFFSET $start ";
            }
            $queryText .= $queryOffset;

            $arguments[ISqlHandler::QUERY_TEXT] = $queryText;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);

            $response = $sqlReader->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getAllRecords($response);
            }

            return $record;
        }

        /** Сосчитать дочерние элементы
         * @return int количество дочерних элементов
         */
        private function calculateChildren():int
        {
            $resultColumnName = 'result';

            $idParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $idParameter[ISqlHandler::VALUE] = intval($this->id);
            $idParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $isHiddenParameter[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $isHiddenParameter[ISqlHandler::VALUE] = intval(self::DEFINE_AS_NOT_HIDDEN);
            $isHiddenParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT 
                COUNT(*) AS '
                . $resultColumnName
                . ' FROM '
                . self::TABLE_NAME
                . ' WHERE '
                . self::PARENT . ' = '
                . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);

            $resultCountChildren = SqlHandler::isNoError($response);

            $result = Core\Common::NO_INDEX;
            if ($resultCountChildren) {
                $record = SqlHandler::getFirstRecord($response);
                $result = Core\Common::setIfExists($resultColumnName,
                    $record,
                    Core\Common::NO_INDEX);
            }
            return $result;
        }
    }
}
