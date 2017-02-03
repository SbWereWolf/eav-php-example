<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:47
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\Core;
    use Assay\Core\PredefinedEntity;
    use Assay\Core\INamedEntity;
    use Assay\DataAccess;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;
    use Assay\InformationsCatalog\StructureInformation\DataType;
    use Assay\InformationsCatalog\StructureInformation\InformationDomain;
    use Assay\InformationsCatalog\StructureInformation\InformationProperty;
    use Assay\InformationsCatalog\StructureInformation\InformationPropertyDomain;
    use Assay\InformationsCatalog\StructureInformation\Rubric;
    use Assay\InformationsCatalog\StructureInformation\RubricInformationProperty;
    use Assay\InformationsCatalog\StructureInformation\TypeEdit;

    /**
     * Позиция рубрики
     */
    class RubricPosition extends PredefinedEntity implements IRubricPosition,
        INamedEntity
    {

        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'information_instance_id';

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'information_instance';
        /** @var string имя родительсклй таблицы */
        const PARENT_TABLE_NAME = Rubric::TABLE_NAME;
        /** @var string колонка в родительской таблицы для связи с дочерней */
        const PARENT_ID = Rubric::ID;
        /** @var string колонка в дочерней таблице для связи с родительской */
        const PARENT = Rubric::EXTERNAL_ID;

        /** @var string имя таблицы значений */
        const VALUE_TABLE_NAME = PropertyContent::TABLE_NAME;
        /** @var string имя колонки для связи позиции и значения свойства */
        const VALUE_PARENT = PropertyContent::PARENT;

        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;
        /** @var string имя таблицы БД для родительской сущности */
        protected $parentTablename = self::PARENT_TABLE_NAME;
        /** @var string колонка в родительской таблицы для связи с дочерней */
        protected $parentIdColumn = self::PARENT_ID;
        /** @var string колонка в дочерней таблице для связи с родительской */
        protected $parentColumn = self::PARENT;

        /** @var string код */
        public $code = self::EMPTY_VALUE;
        /** @var string имя */
        public $name = self::EMPTY_VALUE;
        /** @var string описание */
        public $description = self::EMPTY_VALUE;

        /** @var string ссылка на рубрику */
        public $parentId = self::EMPTY_VALUE;

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

        /** Получить позицию для отображения
         * @param string $propertyName индекс для Названия свойства
         * @param string $propertyDescription индекс для описания свойства
         * @param string $typeEdit индекс для типа редактирования
         * @param string $dataType индекс для типа данных
         * @param string $value индекс для значения
         * @return array массив с набором свойств
         */
        public function getPosition(\string $propertyName = InformationProperty::NAME,
                                    \string $propertyDescription = InformationProperty::DESCRIPTION,
                                    \string $typeEdit = TypeEdit::EXTERNAL_ID,
                                    \string $dataType = DataType::EXTERNAL_ID,
                                    \string $value = PropertyContent::CONTENT):array
        {
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',
                self::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' P.' . InformationProperty::NAME . ' AS ' . $propertyName
                . ' P.' . InformationProperty::DESCRIPTION . ' AS ' . $propertyDescription
                . ' TE.' . TypeEdit::ID . 'AS ' . $typeEdit
                . ' DT.' . DataType::ID . 'AS ' . $dataType
                . ' DT.' . PropertyContent::CONTENT . 'AS ' . $value

                . ' FROM '
                . self::TABLE_NAME . ' AS I '
                . ' JOIN ' . self::PARENT_TABLE_NAME . ' AS R '
                . ' ON I.' . $this->parentColumn . ' = R' . self::PARENT_ID
                . ' JOIN ' . self::VALUE_TABLE_NAME . ' AS V '
                . ' ON I.' . self::ID . ' = V' . self::VALUE_PARENT

                . ' JOIN ' . InformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = RP.' . RubricInformationProperty::PROPERTY
                . ' JOIN ' . InformationPropertyDomain::TABLE_NAME . ' AS PD '
                . ' ON P.' . InformationProperty::ID . ' = PD.' . InformationProperty::EXTERNAL_ID
                . ' JOIN ' . InformationDomain::TABLE_NAME . ' AS D '
                . ' ON D.' . InformationDomain::ID . ' = PD.' . InformationDomain::EXTERNAL_ID

                . ' JOIN ' . TypeEdit::TABLE_NAME . ' AS TE '
                . ' ON D.' . InformationDomain::TYPE_EDIT . ' = TE.' . TypeEdit::ID
                . ' JOIN ' . DataType::TABLE_NAME . ' AS DT '
                . ' ON D.' . InformationDomain::DATA_TYPE . ' = DT.' . DataType::ID

                . ' WHERE '
                . ' RP.' . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' AND D.' . InformationDomain::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' AND TE.' . TypeEdit::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' AND DT.' . DataType::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $result = SqlHandler::readAllRecords($arguments);

            return $result;
        }

        /** Выполнить поиск
         * @param array $filterProperties параметры поиска
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public function search(array $filterProperties, int $start, int $paging):array
        {
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
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::IS_HIDDEN
                . ' , ' . $this->parentColumn
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

        /** Загрузить по коду записи
         * @param string $code код записи
         * @return bool успех выполнения
         */
        public function loadByCode(string $code):bool
        {

            $codeParameter = SqlHandler::setBindParameter(':CODE', $code, \PDO::PARAM_STR);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_NOT_HIDDEN, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . self::ID
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::IS_HIDDEN
                . ' , ' . $this->parentColumn
                . ' FROM '
                . $this->tablename
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

        /** Обновляет (изменяет) запись в БД
         * @return bool успех выполнения
         */
        public function mutateEntity():bool
        {
            $result = false;

            $stored = new RubricPosition();
            $wasReadStored = $stored->loadById($this->id);

            $storedEntity = self::EMPTY_ARRAY;
            $entity = self::EMPTY_ARRAY;
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
            $parentParameter = SqlHandler::setBindParameter(':PARENT', $this->parentId, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' UPDATE '
                . $this->tablename
                . ' SET '
                . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::NAME . ' = ' . $nameParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::DESCRIPTION . ' = ' . $descriptionParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . $this->parentColumn . ' = ' . $parentParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' RETURNING '
                . self::ID
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . $this->parentColumn
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $nameParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $parentParameter;

            $record = SqlHandler::writeOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);;
            }
            return $result;
        }

        /** Добавить дочернюю сущность
         * @return bool успех выполнения
         */
        public function addPredefinedEntity():bool
        {
            $propertyKey = InformationProperty::EXTERNAL_ID;

            $propertiesId = $this->getPropertiesId($propertyKey);

            $isSuccess = $propertiesId != self::EMPTY_ARRAY;
            if ($isSuccess) {
                $isSuccess = $this->insertPredefined();
            }

            $isChildSuccess = false;
            if ($isSuccess) {
                $isChildSuccess = $this->insertPropertyValue($propertiesId, $propertyKey);
            }

            return $isChildSuccess;
        }

        /** Добавить записи "потомки" дочерней
         * @param array $foreignKeysSet внешние ключи для записей потомков
         * @param string $propertyKey значение внешнего ключа записи информационного свойства
         * @return bool
         */
        private function insertPropertyValue(array $foreignKeysSet, string $propertyKey):bool
        {
            $isChildSuccess = count($foreignKeysSet) > 0;

            $parentValue = $this->id;

            foreach ($foreignKeysSet as $foreignKey) {

                $propertyKeyValue = Core\Common::setIfExists($propertyKey, $foreignKey, self::EMPTY_VALUE);

                $isSuccess = $propertyKeyValue != self::EMPTY_VALUE;
                if ($isSuccess) {
                    $propertyValue = new PropertyContent();

                    $propertyValue->parentId = $parentValue;
                    $propertyValue->propertyId = $propertyKeyValue;

                    $isSuccess = $propertyValue->addPredefinedEntity();
                }
                $isChildSuccess = $isSuccess && $isChildSuccess;

            }
            return $isChildSuccess;
        }

        /** Получить идентификаторы всех свойств рубрики этого экземпляра
         * @param string $propertyKey индекс для элемента с идентификатором
         * @return array массив с идентификаторами
         */
        private function getPropertiesId(string $propertyKey):array
        {
            $rubric = new Rubric();
            $wasRubricLoad = $rubric->loadById($this->parentId);

            $propertiesId = self::EMPTY_ARRAY;
            if ($wasRubricLoad) {

                $propertiesId = $rubric->readAllPropertyId($propertyKey);
            }
            return $propertiesId;
        }
    }
}
