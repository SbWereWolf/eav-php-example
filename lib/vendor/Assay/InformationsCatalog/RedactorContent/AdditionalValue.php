<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:57
 */
namespace Assay\InformationsCatalog\RedactorContent {

    use Assay\Core\PredefinedEntity;
    use Assay\Core\Common;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;
    use Assay\InformationsCatalog\DataInformation\PropertyContent;

    /**
     * Пользовательские данные
     */
    class AdditionalValue extends PredefinedEntity
    {

        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'additional_value_id';

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'additional_value';
        /** @var string имя родительсклй таблицы */
        const PARENT_TABLE_NAME = PropertyContent::TABLE_NAME;
        /** @var string колонка в родительской таблицы для ссылки из дочерней */
        const PARENT = PropertyContent::ID;
        /** @var string колонка в дочерней таблице для ссылки на родительскую запись */
        const CHILD = PropertyContent::EXTERNAL_ID;

        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;
        /** @var string имя таблицы БД для родительской сущности */
        protected $parentTablename = self::PARENT_TABLE_NAME;
        /** @var string колонка в родительской таблицы для связи с дочерней */
        protected $parentColumn = self::PARENT;
        /** @var string колонка в дочерней таблице для связи с родительской */
        protected $childColumn = self::CHILD;

        /** @var string колонка для ссылки на пользователя информационного каталога */
        const REDACTOR = Redactor::EXTERNAL_ID;
        /** @var string значение свойства */
        const VALUE = 'value';

        /** @var string ссылка на содержимое свойства */
        public $linkToParent = self::EMPTY_VALUE;
        /** @var string редактор */
        public $redactorId = self::EMPTY_VALUE;
        /** @var string дополнительное значение свойства */
        public $value = self::EMPTY_VALUE;

        /** Добавить дочернюю сущность
         * @return bool успех выполнения
         */
        public function addPredefinedEntity():bool
        {
            $isSuccess = $this->insertPredefined();
            return $isSuccess;
        }
        
        /** вставить в таблицу запись дочерней сущности
         * @return bool успех выполнения
         */
        protected function insertPredefined():bool
        {
            $parentParameter = SqlHandler::setBindParameter(':PARENT', $this->linkToParent, \PDO::PARAM_INT);
            $redactorParameter = SqlHandler::setBindParameter(':REDACTOR', $this->redactorId, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'INSERT INTO  ' . $this->tablename
                . ' ('
                . $this->childColumn
                . ' , ' . self::REDACTOR
                . ')'
                . ' VALUES  ('
                . $parentParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . $redactorParameter[ISqlHandler::PLACEHOLDER]
                . ')'
                . ' RETURNING '
                . self::ID
                . ' , ' . $this->childColumn
                . ' , ' . self::REDACTOR
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $parentParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $redactorParameter;

            $parent = SqlHandler::writeOneRecord($arguments);

            $isSuccess = $parent != ISqlHandler::EMPTY_ARRAY;
            if ($isSuccess) {
                $isSuccess = $this->setByNamedValue($parent);
            }

            return $isSuccess;
        }

        public function setByNamedValue(array $namedValue):bool
        {

            $result = parent::setByNamedValue($namedValue);

            $redactor = Common::setIfExists(self::REDACTOR, $namedValue, self::EMPTY_VALUE);
            if ($redactor != self::EMPTY_VALUE) {
                $this->redactorId = $redactor;
            }
            $value = Common::setIfExists(self::VALUE, $namedValue, self::EMPTY_VALUE);
            if ($value != self::EMPTY_VALUE) {
                $this->value = $value;
            }

            return $result;
        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {
            $result = parent::toEntity();

            $result[self::REDACTOR] = $this->redactorId;
            $result[self::VALUE] = $this->value;

            return $result;
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
                . ' , ' . self::REDACTOR
                . ' , ' . self::VALUE
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

        /** Обновляет (изменяет) запись в БД
         * @return bool успех выполнения
         */
        public function mutateEntity():bool
        {
            $result = false;

            $stored = new AdditionalValue();
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
            $valueParameter = SqlHandler::setBindParameter(':VALUE', $this->value, \PDO::PARAM_STR);


            $arguments[ISqlHandler::QUERY_TEXT] =
                ' UPDATE '
                . $this->tablename
                . ' SET '
                . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::VALUE . ' = ' . $valueParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' RETURNING '
                . self::ID
                . ' , ' . self::IS_HIDDEN
                . ' , ' . $this->childColumn
                . ' , ' . self::REDACTOR
                . ' , ' . self::VALUE
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $valueParameter;

            $record = SqlHandler::writeOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);;
            }
            return $result;
        }
    }
}
