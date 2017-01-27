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

            $codeParameter = SqlHandler::setBindParameter(':CODE',$code,\PDO::PARAM_STR);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',self::DEFINE_AS_NOT_HIDDEN,\PDO::PARAM_INT);

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

            $record = SqlHandler::readOneRecord($arguments);

            $isSuccessfulRead = $record != ISqlHandler::EMPTY_ARRAY;

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

            $oneParameter = SqlHandler::setBindParameter(':ID',$id,\PDO::PARAM_INT);

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

            $record = SqlHandler::readOneRecord($arguments);
            $result = $record != ISqlHandler::EMPTY_ARRAY;

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

            $codeParameter = SqlHandler::setBindParameter(':CODE',$this->code,\PDO::PARAM_STR);
            $descriptionParameter = SqlHandler::setBindParameter(':DESCRIPTION',$this->description,\PDO::PARAM_STR);
            $idParameter = SqlHandler::setBindParameter(':ID',$this->id,\PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',$this->isHidden,\PDO::PARAM_INT);
            $nameParameter = SqlHandler::setBindParameter(':NAME',$this->name,\PDO::PARAM_STR);
            $searchTypeParameter = SqlHandler::setBindParameter(':SEARCH_TYPE',$this->searchType,\PDO::PARAM_INT);
            $typeEditParameter = SqlHandler::setBindParameter(':TYPE_EDIT',$this->typeEdit,\PDO::PARAM_INT);
            $dataTypeParameter = SqlHandler::setBindParameter(':DATA_TYPE',$this->dataType,\PDO::PARAM_INT);

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

            $record = SqlHandler::readOneRecord($arguments);
            $result = $record != ISqlHandler::EMPTY_ARRAY;

            if($result){
                $result = $this->setByNamedValue($record);
            }

            return $result;
        }
    }
}
