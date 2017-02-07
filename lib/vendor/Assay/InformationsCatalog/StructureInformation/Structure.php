<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:09
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core;
    use Assay\DataAccess;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    /**
     * Древовидная структура
     */
    class Structure extends Core\NamedEntity implements IStructure

    {
        /** @var string константа для не пустого значения */
        const EMPTY_VALUE = Core\ICommon::EMPTY_VALUE;

        /** @var string имя таблицы */
        const TABLE_NAME = 'structure';

        /** @var string родительский элемент */
        public $linkToParent = self::EMPTY_VALUE;

        /** @var string имя таблицы */
        protected $tablename = self::TABLE_NAME;

        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return bool успех выполнения
         */
        public function loadById(string $id):bool
        {
            $oneParameter = SqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . self::ID
                . ' , ' . self::CHILD
                . ' , btrim(' . self::CODE . ') AS "' . self::CODE . '"'
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::IS_HIDDEN
                . ' FROM '
                . $this->tablename
                . ' WHERE '
                . self::ID . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER]
                . '
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $record = SqlHandler::readOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }

            return $result;
        }

        /** Установить свойства экземпляра в соответствии со значениями
         * @param array $namedValue массив значений
         * @return bool успех выполнения
         */
        public function setByNamedValue(array $namedValue):bool
        {

            $result = parent::setByNamedValue($namedValue);
            $linkToParent = Core\Common::setIfExists(self::CHILD, $namedValue, self::EMPTY_VALUE);
            if ($linkToParent != self::EMPTY_VALUE) {
                $this->linkToParent = $linkToParent;
            }

            return $result;
        }

        /** Добавить запись в БД на основе экземпляра
         * @param string $parentCode
         * @return bool успех выполнения
         */
        public function setLinkToParent(string $parentCode):bool
        {
            $stored = new Structure();
            $isSuccess = $stored->loadByCode($parentCode);

            $result = false;
            if ($isSuccess) {
                $this->linkToParent = $stored->id;
                $result = true;
            }
            return $result;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успех выполнения
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

        /** Обновить данные в БД
         * @return bool успех выполнения
         */
        protected function updateEntity():bool
        {
            $codeParameter = SqlHandler::setBindParameter(':CODE', $this->code, \PDO::PARAM_STR);
            $descriptionParameter = SqlHandler::setBindParameter(':DESCRIPTION', $this->description, \PDO::PARAM_STR);
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);
            $nameParameter = SqlHandler::setBindParameter(':NAME', $this->name, \PDO::PARAM_STR);
            $linkToParentParameter = SqlHandler::setBindParameter(':LINK_TO_PARENT', $this->linkToParent, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'UPDATE '
                . self::TABLE_NAME
                . ' SET '
                . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::CHILD . ' = ' . $linkToParentParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::NAME . ' = ' . $nameParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::DESCRIPTION . ' = ' . $descriptionParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' RETURNING '
                . ' btrim(' . self::CODE . ') AS "' . self::CODE . '"'
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::CHILD
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::ID
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $nameParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $linkToParentParameter;

            $record = SqlHandler::writeOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);;
            }

            return $result;
        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {

            $result = parent::toEntity();
            $result [self::CHILD] = $this->linkToParent;

            return $result;
        }

        /** Чтение данных из БД по коду
         * @param string $code код записи
         * @return bool успех выполнения
         */
        public function loadByCode(string $code):bool
        {
            $codeParameter = SqlHandler::setBindParameter(':CODE', $code, \PDO::PARAM_STR);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',
                self::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . self::ID
                . ' , ' . self::CHILD
                . ' , btrim(' . self::CODE . ') AS "' . self::CODE . '"'
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::IS_HIDDEN
                . ' FROM '
                . self::TABLE_NAME
                . ' WHERE '
                . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' AND ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $record = SqlHandler::readOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }

            return $result;
        }

        /** Добавить дочерний элемент
         * @return string идентификатор добавленого элемента
         */
        public function addChild():string
        {

            $child = new Structure();
            $child->addEntity();

            $child->linkToParent = $this->id;
            $isSuccess = $child->mutateEntity();

            $result = self::EMPTY_VALUE;
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
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_NOT_HIDDEN, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT 
                ' . self::NAME . ' AS ' . $nameKey
                . ' FROM '
                . self::TABLE_NAME
                . ' WHERE '
                . self::CHILD . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $result = SqlHandler::readAllRecords($arguments);

            return $result;
        }

        /** Получить коды дочерних элементов
         * @param string $codeKey значение для индекса элемента с кодом
         * @return array массив кодов
         */
        public function getChildrenCodes(string $codeKey = Core\INamedEntity::CODE):array
        {
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_NOT_HIDDEN, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT'
                . ' btrim(' . self::CODE . ') AS "' . $codeKey . '"'
                . ' FROM '
                . $this->tablename
                . ' WHERE '
                . self::CHILD . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER] .
                ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $result = SqlHandler::readAllRecords($arguments);

            return $result;
        }

        /** Получить идентификатор ролительского элемнта
         * @return string идентификатор
         */
        public function getLinkToParent():string
        {
            $result = strval($this->linkToParent);
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
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);

            $queryText =
                '
WITH RECURSIVE children ( id, structure_id, code, name,level ) AS
(
  SELECT
    SC.' . self::ID . ',
    SC.' . self::CHILD . ',
    btrim(SC.' . self::CODE . ') AS "' . self::CODE . '",
    SC.' . self::NAME . ',
    0
  FROM ' . self::TABLE_NAME . ' SC
  WHERE SC.id = ' . $idParameter[ISqlHandler::PLACEHOLDER] . '
  UNION
  SELECT
    SN.' . self::ID . ',
    SN.' . self::CHILD . ',
    btrim(SN.' . self::CODE . ') AS "' . self::CODE . '",
    SN.' . self::NAME . ',
    level + 1
  FROM ' . self::TABLE_NAME . ' SN
    INNER JOIN children C
      ON (C.' . self::CHILD . ' = SN.' . self::ID . ')
)
SELECT '
                . 'btrim(' . self::CODE . ') AS "' . self::CODE . '"'
                . ' , ' . self::NAME
                . '
FROM children
ORDER BY level DESC
;
';
            $arguments[ISqlHandler::QUERY_TEXT] = $queryText;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;

            $records = SqlHandler::readAllRecords($arguments);

            return $records;
        }

        /** Получить элменты карты структуры
         * @param string $code код корневого элмента
         * @return array массив с элементами карты
         */
        public static function getMap(string $code = ' '):array
        {
            $codeParameter = SqlHandler::setBindParameter(':CODE', $code, \PDO::PARAM_STR);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_NOT_HIDDEN, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                '
WITH RECURSIVE nodes ( id, structure_id, code, name, path, level ) AS
(
  SELECT
    SC.' . self::ID . ',
    SC.' . self::CHILD . ',
    btrim(SC.' . self::CODE  .') AS "'.self::CODE.'",
    SC.' . self::NAME . ',
    CAST ( btrim(SC.' . self::CODE . ') AS text ) AS "'.self::CODE.'",
    0
  FROM ' . self::TABLE_NAME . ' SC
  WHERE SC.' . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER] . ' 
    OR (
        ' . $codeParameter[ISqlHandler::PLACEHOLDER] . ' = \' \' 
        AND SC.' . self::CHILD . ' IS NULL
    )
  UNION
  SELECT
    SN.' . self::ID . ',
    SN.' . self::CHILD . ',
    btrim( SN.' . self::CODE  .') AS "'.self::CODE.'",
    SN.' . self::NAME . ',
    CAST ( N.path || \'-\'|| btrim(SN.' . self::CODE . ')  AS text ) AS "'.self::CODE.'",
    level + 1
  FROM ' . self::TABLE_NAME . ' SN
    INNER JOIN nodes N
      ON (N.id = SN.' . self::CHILD . ')
  WHERE SN.'
                . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER] . '
)
SELECT
  btrim(' . self::CODE  .') AS "'.self::CODE.'",
  ' . self::NAME . ',
  path,
  level
FROM nodes
ORDER BY path
;
';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $record = SqlHandler::readAllRecords($arguments);

            return $record;
        }

        /** Выполнить поиск
         * @param string $searchString поисковая строка
         * @param string $structureCode код корневого элемента
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public static function search(string $searchString = Core\ICommon::EMPTY_VALUE,
                                      string $structureCode = Core\ICommon::EMPTY_VALUE,
                                      int $start = 0,
                                      int $paging = 0):array
        {

            $searchStringParameter = SqlHandler::setBindParameter(':SEARCH_STRING', $searchString, \PDO::PARAM_STR);
            $structureCodeParameter = SqlHandler::setBindParameter(':CODE', $structureCode, \PDO::PARAM_STR);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_NOT_HIDDEN, \PDO::PARAM_INT);

            $queryText =
                'SELECT '
                . ' btrim(' . self::CODE .') AS "'.self::CODE.'"'
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' FROM '
                . self::TABLE_NAME . ' AS SO '
                . ' WHERE SO.'
                . self::IS_HIDDEN
                . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER];

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $queryWithString = '';
            if ($searchStringParameter[ISqlHandler::VALUE] != DataAccess\ICommon::EMPTY_VALUE) {

                $queryWithString =
                    ' AND ( SO.'
                    . self::NAME . ' ILIKE ' . DataAccess\ICommon::WILDCARD_SYMBOL . '\'%\'||'
                    . $searchStringParameter[ISqlHandler::PLACEHOLDER]
                    . '||\'%\''
                    . ' OR SO.' . self::DESCRIPTION . ' ILIKE '
                    . '\'%\'||' . $searchStringParameter[ISqlHandler::PLACEHOLDER] . '||\'%\' )';

                $arguments[ISqlHandler::QUERY_PARAMETER][] = $searchStringParameter;
            }
            $queryText .= $queryWithString;

            $queryWithCode = '';
            if ($structureCodeParameter[ISqlHandler::VALUE] != DataAccess\ICommon::EMPTY_VALUE) {

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
            SC.' . self::CHILD . ',
            btrim( SC.' . self::CODE  .') AS "'.self::CODE.'",
            0
          FROM ' . self::TABLE_NAME . ' SC
          WHERE SC.' . self::ID . ' = SO.' . self::ID . '
          UNION
          SELECT
            SN.' . self::ID . ',
            SN.' . self::CHILD . ',
            btrim(SN.' . self::CODE  .') AS "'.self::CODE.'",
            level + 1
          FROM ' . self::TABLE_NAME . ' SN
            INNER JOIN children C
              ON (C.' . self::CHILD . ' = SN.' . self::ID . ')
          WHERE
            SN.' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER] . '
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

            $pagingString = SqlHandler::getPagingCondition($start, $paging);
            $queryText .= $pagingString;

            $arguments[ISqlHandler::QUERY_TEXT] = $queryText;

            $record = SqlHandler::readAllRecords($arguments);

            return $record;
        }

        /** Сосчитать дочерние элементы
         * @return int количество дочерних элементов
         */
        private function calculateChildren():int
        {
            $resultColumnName = 'result';

            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN'
                , self::DEFINE_AS_NOT_HIDDEN
                , \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT 
                COUNT(*) AS ' . $resultColumnName
                . ' FROM '
                . self::TABLE_NAME
                . ' WHERE '
                . self::CHILD . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;

            $record = SqlHandler::readOneRecord($arguments);

            $result = Core\Common::NO_INDEX;
            if ($record != DataAccess\ICommon::EMPTY_ARRAY) {
                $result = Core\Common::setIfExists($resultColumnName,
                    $record,
                    Core\Common::NO_INDEX);
            }
            return $result;
        }
    }
}
