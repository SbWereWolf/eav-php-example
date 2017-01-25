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

class ParentEntity extends PrimitiveData implements INamedEntity
{

    /** @var null константа значение не задано для ссылочных типов */
    const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
    /** @var array константа значение не задано для массивов */
    const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

    /** @var string имя таблицы БД для хранения сущности */
    const TABLE_NAME = 'parent_entity';

    /** @var string имя таблицы БД для хранения сущности */
    protected $tablename = self::TABLE_NAME;

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

        $codeParameter = SqlHandler::setBindParameter(':CODE',$code,\PDO::PARAM_STR);
        $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',self::DEFINE_AS_NOT_HIDDEN,\PDO::PARAM_INT);

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

        $record = SqlHandler::readOneRecord($arguments);

        $result = false;
        if ($record != ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;
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

        $oneParameter = SqlHandler::setBindParameter(':ID',$id,\PDO::PARAM_INT);

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

        $record = SqlHandler::readOneRecord($arguments);

        $result = false;
        if ($record != ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
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

        $stored = new ParentEntity();
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

        $codeParameter = SqlHandler::setBindParameter(':CODE',$this->code,\PDO::PARAM_STR);
        $descriptionParameter = SqlHandler::setBindParameter(':DESCRIPTION',$this->description,\PDO::PARAM_STR);
        $idParameter = SqlHandler::setBindParameter(':ID',$this->id,\PDO::PARAM_INT);
        $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',$this->isHidden,\PDO::PARAM_INT);
        $nameParameter = SqlHandler::setBindParameter(':NAME',$this->name,\PDO::PARAM_STR);

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

        $id = SqlHandler::setBindParameter(':ID',$this->id,\PDO::PARAM_INT);
        $isHidden = SqlHandler::setBindParameter(':IS_HIDDEN',self::DEFINE_AS_HIDDEN,\PDO::PARAM_INT);

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
        $record = self::EMPTY_ARRAY;
        if ($isSuccessfulRead) {
            $record = SqlHandler::getFirstRecord($response);
        }

        $this->id = Common::setIfExists(self::ID, $record, self::EMPTY_VALUE);
        $this->isHidden = Common::setIfExists(self::IS_HIDDEN, $record, self::EMPTY_VALUE);

        $result = $this->id != self::EMPTY_VALUE &&  $this->isHidden  != self::EMPTY_VALUE;

        return $result;
    }


    public function addParentEntity():bool
    {
        $result = false;
        return $result;
    }

}
