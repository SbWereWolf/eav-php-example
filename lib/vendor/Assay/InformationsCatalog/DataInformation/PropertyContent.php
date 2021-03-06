<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:48
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\Core\PredefinedEntity;
    use Assay\Core\Common;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;
    use Assay\InformationsCatalog\StructureInformation\InformationProperty;

    /**
     * Значения свойства позиции рубрики
     */
    class PropertyContent extends PredefinedEntity
    {

        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'property_content_id';

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'property_content';
        /** @var string имя родительсклй таблицы */
        const PARENT_TABLE_NAME = RubricPosition::TABLE_NAME;
        /** @var string колонка в родительской таблицы для связи с дочерней */
        const PARENT = RubricPosition::ID;
        /** @var string имя колонки для ссылки на родительскую запись */
        const CHILD = RubricPosition::EXTERNAL_ID;

        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;
        /** @var string имя таблицы БД для родительской сущности */
        protected $parentTablename = self::PARENT_TABLE_NAME;
        /** @var string колонка в родительской таблицы для связи с дочерней */
        protected $parentColumn = self::PARENT;
        /** @var string колонка в дочерней таблице для связи с родительской */
        protected $childColumn = self::CHILD;

        /** @var string колонка для ссылки на рубрику */
        const PROPERTY = InformationProperty::EXTERNAL_ID;
        /** @var string значение свойства */
        const CONTENT = 'content';

        /** @var string ссылка на рубрику */
        public $linkToParent = self::EMPTY_VALUE;
        /** @var string свойство */
        public $propertyId = self::EMPTY_VALUE;
        /** @var string значение свойства */
        public $content = self::EMPTY_VALUE;

        /** вставить в таблицу запись дочерней сущности
         * @return bool успех выполнения
         */
        protected function insertPredefined():bool
        {
            $parentParameter = SqlHandler::setBindParameter(':PARENT', $this->linkToParent, \PDO::PARAM_INT);
            $propertyParameter = SqlHandler::setBindParameter(':PROPERTY', $this->propertyId, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'INSERT INTO  ' . $this->tablename
                . ' ('
                . $this->childColumn
                . ' , ' . self::PROPERTY
                . ')'
                . ' VALUES  ('
                . $parentParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . $propertyParameter[ISqlHandler::PLACEHOLDER]
                . ')'
                . ' RETURNING '
                . self::ID
                . ' , ' . $this->childColumn
                . ' , ' . self::PROPERTY
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $parentParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $propertyParameter;

            $parent = SqlHandler::writeOneRecord($arguments);

            $isSuccess = $parent != ISqlHandler::EMPTY_ARRAY;
            if ($isSuccess) {
                $isSuccess = $this->setByNamedValue($parent);
            }

            return $isSuccess;
        }

        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return bool успех выполнения
         */
        public function loadById(string $id):bool
        {

            $oneParameter = SqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . self::ID
                . ' , ' . self::PROPERTY
                . ' , ' . self::CONTENT
                . ' , ' . self::IS_HIDDEN
                . ' , ' . $this->childColumn
                . ' FROM '
                . $this->tablename
                . ' WHERE '
                . self::ID . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER]
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $record = SqlHandler::readOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }

            return $result;
        }
        
        public function setByNamedValue(array $namedValue):bool
        {

            $result = parent::setByNamedValue($namedValue);

            $propertyId = Common::setIfExists(self::PROPERTY, $namedValue, self::EMPTY_VALUE);
            if ($propertyId != self::EMPTY_VALUE) {
                $this->propertyId = $propertyId;
            }
            $content = Common::setIfExists(self::CONTENT, $namedValue, self::EMPTY_VALUE);
            if ($content != self::EMPTY_VALUE) {
                $this->content = $content;
            }

            return $result;
        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {
            $result = parent::toEntity();

            $result[self::PROPERTY] = $this->linkToParent;
            $result[self::CONTENT] = $this->content;

            return $result;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успех выполнения
         */
        public function mutateEntity():bool
        {
            $result = false;

            $stored = new PropertyContent();
            $wasReadStored = $stored->loadById($this->id);

            $storedEntity = self::EMPTY_ARRAY;
            $entity = self::EMPTY_ARRAY;
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

            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);
            $contentParameter = SqlHandler::setBindParameter(':CONTENT', $this->content, \PDO::PARAM_STR);


            $arguments[ISqlHandler::QUERY_TEXT] =
                ' UPDATE '
                . $this->tablename
                . ' SET '
                . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::CONTENT . ' = ' . $contentParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' RETURNING '
                . self::ID
                . ' , ' . self::IS_HIDDEN
                . ' , ' . $this->childColumn
                . ' , ' . self::PROPERTY
                . ' , ' . self::CONTENT
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $contentParameter;

            $record = SqlHandler::writeOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);;
            }
            return $result;
        }

    }
}
