<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.01.2017
 * Time: 1:16
 */

namespace Assay\Core;


use Assay\DataAccess\ISqlHandler;
use Assay\DataAccess\SqlHandler;

class PredefinedEntity extends PrimitiveData implements IPredefinedEntity
{
    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'child_entity_id';

    /** @var string имя таблицы БД для хранения сущности */
    const TABLE_NAME = 'child_entity';
    /** @var string имя родительсклй таблицы */
    const PARENT_TABLE_NAME = 'child_entity_parent';
    /** @var string колонка в родительской таблицы для связи с дочерней */
    const PARENT_ID = 'id';
    /** @var string имя в дочерней таблице для связи с родительской */
    const PARENT = 'child_entity_parent_id';

    /** @var string имя таблицы БД для хранения сущности */
    protected $tablename = self::TABLE_NAME;
    /** @var string имя таблицы БД для родительской сущности */
    protected $parentTablename = self::PARENT_TABLE_NAME;
    /** @var string колонка в родительской таблицы для связи с дочерней */
    protected $parentIdColumn = self::PARENT_ID;
    /** @var string колонка в дочерней таблице для связи с родительской */
    protected $parentColumn = self::PARENT;


    /** @var string ссылка на рубрику */
    public $parentId = self::EMPTY_VALUE;


    /** Прочитать запись из БД
     * @param string $id идентификатор записи
     * @return bool успех выполнения
     */
    public function loadById(string $id):bool
    {

        $idParameter = SqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . $this->parentColumn
            . ' FROM '
            . $this->tablename
            . ' WHERE '
            . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
            . ';';
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;

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

        $parent = Common::setIfExists(self::PARENT, $namedValue, self::EMPTY_VALUE);
        if ($parent != self::EMPTY_VALUE) {
            $this->parentId = $parent;
        }

        return $result;
    }

    /** Формирует массив из свойств экземпляра
     * @return array массив свойств экземпляра
     */
    public function toEntity():array
    {
        $result = parent::toEntity();
        $result[self::PARENT] = $this->parentId;

        return $result;
    }

    /** Прочитать данные экземпляра из БД
     * @return bool успех выполнения
     */
    public function getStored():bool
    {
        $result = $this->loadById($this->id);
        return $result;
    }

    /** Обновляет (изменяет) запись в БД
     * @return bool успех выполнения
     */
    public function mutateEntity():bool
    {
        $result = false;

        $stored = new PredefinedEntity();
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

        $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
        $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'UPDATE '
            . $this->tablename
            . ' SET '
            . ' , ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
            . ' WHERE '
            . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . $this->parentColumn
            . ';';


        $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

        $record = SqlHandler::writeOneRecord($arguments);

        $result = false;
        if ($record != ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);;
        }
        return $result;
    }

    /** Скрыть сущность
     * @return bool успех выполнения
     */
    public function hideEntity():bool
    {

        $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
        $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_HIDDEN, \PDO::PARAM_INT);

        $arguments[SqlHandler::QUERY_TEXT] = '
            UPDATE ' . $this->tablename . '
            SET ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
            . ' WHERE ' . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . $this->parentColumn
            . ' ; ';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

        $record = SqlHandler::readOneRecord($arguments);

        $result = false;
        if ($record != self::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;
    }


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
        $parentParameter = SqlHandler::setBindParameter(':PARENT', $this->parentId, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'INSERT INTO  ' . $this->tablename
            . ' ('
            . $this->parentColumn
            . ')'
            . ' VALUES  ('
            . $parentParameter[ISqlHandler::PLACEHOLDER]
            . ')'
            . ' RETURNING '
            . self::ID
            . ' , ' . $this->parentColumn
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $parentParameter;

        $parent = SqlHandler::writeOneRecord($arguments);

        $isSuccess = $parent != ISqlHandler::EMPTY_ARRAY;
        if ($isSuccess) {
            $isSuccess = $this->setByNamedValue($parent);
        }

        return $isSuccess;
    }
}
