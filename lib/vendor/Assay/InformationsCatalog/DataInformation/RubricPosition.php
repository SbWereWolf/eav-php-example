<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:47
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\Core;
    use Assay\Core\INamedEntity;
    use Assay\Core\PredefinedEntity;
    use Assay\DataAccess;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;
    use Assay\InformationsCatalog\RedactorContent\AdditionalValue;
    use Assay\InformationsCatalog\RedactorContent\Redactor;
    use Assay\InformationsCatalog\StructureInformation\DataType;
    use Assay\InformationsCatalog\StructureInformation\DomainInformationProperty;
    use Assay\InformationsCatalog\StructureInformation\InformationDomain;
    use Assay\InformationsCatalog\StructureInformation\InformationProperty;
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
        const EXTERNAL_ID = 'rubric_position_id';

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'rubric_position';
        /** @var string имя родительсклй таблицы */
        const PARENT_TABLE_NAME = Rubric::TABLE_NAME;
        /** @var string колонка в родительской таблицы для связи с дочерней */
        const PARENT = Rubric::ID;
        /** @var string колонка в дочерней таблице для связи с родительской */
        const CHILD = Rubric::EXTERNAL_ID;

        /** @var string имя таблицы содержимого */
        const CONTENT_TABLE_NAME = PropertyContent::TABLE_NAME;
        /** @var string имя колонки для связи позиции и содержания свойства */
        const CONTENT_LINK = PropertyContent::CHILD;
        /** @var string имя таблицы дополнительных значений */
        const VALUE_TABLE_NAME = AdditionalValue::TABLE_NAME;
        /** @var string имя колонки для связи дополнительного значения и содержимого свойства */
        const VALUE_LINK = AdditionalValue::CHILD;

        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;
        /** @var string имя таблицы БД для родительской сущности */
        protected $parentTablename = self::PARENT_TABLE_NAME;
        /** @var string колонка в родительской таблицы для связи с дочерней */
        protected $parentColumn = self::PARENT;
        /** @var string колонка в дочерней таблице для связи с родительской */
        protected $childColumn = self::CHILD;

        /** @var string код */
        public $code = self::EMPTY_VALUE;
        /** @var string имя */
        public $name = self::EMPTY_VALUE;
        /** @var string описание */
        public $description = self::EMPTY_VALUE;

        /** @var string ссылка на рубрику */
        public $linkToParent = self::EMPTY_VALUE;

        public function setByNamedValue(array $namedValue):bool
        {

            $result = parent::setByNamedValue($namedValue);

            $code = Core\Common::setIfExists(self::CODE, $namedValue, self::EMPTY_VALUE);
            if ($code != self::EMPTY_VALUE) {
                $this->code = $code;
            }

            $description = Core\Common::setIfExists(self::DESCRIPTION, $namedValue, self::EMPTY_VALUE);
            if ($description != self::EMPTY_VALUE) {
                $this->description = $description;
            }

            $name = Core\Common::setIfExists(self::NAME, $namedValue, self::EMPTY_VALUE);
            if ($name != self::EMPTY_VALUE) {
                $this->name = $name;
            }

            return $result;
        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {
            $result = parent::toEntity();

            $result [self::CODE] = $this->code;
            $result [self::NAME] = $this->name;
            $result [self::DESCRIPTION] = $this->description;

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

        /** Получить позицию для отображения
         * @param string $propertyCode код свойства
         * @param string $content индекс для значения
         * @param string $typeEdit индекс для типа редактирования
         * @param string $dataType индекс для типа данных
         * @return array массив с набором свойств
         */
        public function getPositionContent(
            string $propertyCode = InformationProperty::TABLE_NAME,
            string $content = PropertyContent::CONTENT,
            string $typeEdit = TypeEdit::EXTERNAL_ID,
            string $dataType = DataType::EXTERNAL_ID):array
        {
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',
                self::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' TE.' . TypeEdit::ID . ' AS ' . $typeEdit
                . ' , DT.' . DataType::ID . ' AS ' . $dataType
                . ' , C.' . PropertyContent::CONTENT . ' AS ' . $content
                . ' , P.' . InformationProperty::CODE .' AS '. $propertyCode

                . ' FROM '
                . $this->tablename . ' AS RP '
                . ' JOIN ' . self::CONTENT_TABLE_NAME . ' AS C '
                . ' ON RP.' . self::ID . ' = C.' . self::CONTENT_LINK
                . ' JOIN ' . $this->parentTablename . ' AS R '
                . ' ON RP.' . $this->childColumn . ' = R.' . $this->parentColumn
                . ' JOIN ' . RubricInformationProperty::TABLE_NAME . ' AS RI '
                . ' ON R.' . Rubric::ID . ' = RI.' . RubricInformationProperty::LEFT

                . ' JOIN ' . InformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = RI.' . RubricInformationProperty::RIGHT
                . ' AND P.' . InformationProperty::ID . ' = C.' . PropertyContent::PROPERTY
                . ' JOIN ' . DomainInformationProperty::TABLE_NAME . ' AS DP '
                . ' ON P.' . InformationProperty::ID . ' = DP.' . DomainInformationProperty::RIGHT
                . ' JOIN ' . InformationDomain::TABLE_NAME . ' AS D '
                . ' ON D.' . InformationDomain::ID . ' = DP.' . DomainInformationProperty::LEFT

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

        /** Получить позицию для отображения
         * @param string $property код свойства
         * @param string $typeEdit индекс для типа редактирования
         * @param string $dataType индекс для типа данных
         * @param string $value индекс для значения
         * @return array массив с набором свойств
         */
        public function getPositionValue(
            string $property = InformationProperty::CODE,
            string $value = AdditionalValue::VALUE,
            string $typeEdit = TypeEdit::EXTERNAL_ID,
            string $dataType = DataType::EXTERNAL_ID
        ):array
        {
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',
                self::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' TE.' . TypeEdit::ID . ' AS ' . $typeEdit
                . ' , DT.' . DataType::ID . ' AS ' . $dataType
                . ' , P.' . InformationProperty::CODE . ' AS ' . $property
                . ' , V.' . AdditionalValue::VALUE . ' AS ' . $value

                . ' FROM '
                . $this->tablename . ' AS RP '
                . ' JOIN ' . self::CONTENT_TABLE_NAME . ' AS C '
                . ' ON RP.' . self::ID . ' = C.' . self::CONTENT_LINK

                . ' JOIN ' . self::VALUE_TABLE_NAME . ' AS V '
                . ' ON C.' . PropertyContent::ID . ' = V.' . self::VALUE_LINK

                . ' JOIN ' . $this->parentTablename . ' AS R '
                . ' ON RP.' . $this->childColumn . ' = R.' . $this->parentColumn
                . ' JOIN ' . RubricInformationProperty::TABLE_NAME . ' AS RI '
                . ' ON R.' . Rubric::ID . ' = RI.' . RubricInformationProperty::LEFT

                . ' JOIN ' . InformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = RI.' . RubricInformationProperty::RIGHT
                . ' AND P.' . InformationProperty::ID . ' = C.' . PropertyContent::PROPERTY
                . ' JOIN ' . DomainInformationProperty::TABLE_NAME . ' AS DP '
                . ' ON P.' . InformationProperty::ID . ' = DP.' . DomainInformationProperty::RIGHT
                . ' JOIN ' . InformationDomain::TABLE_NAME . ' AS D '
                . ' ON D.' . InformationDomain::ID . ' = DP.' . DomainInformationProperty::LEFT

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
                . ' , ' . $this->childColumn
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

            $stored = new RubricPosition();
            $wasReadStored = $stored->loadById($this->id);

            $storedEntity = self::EMPTY_ARRAY;
            $entity = self::EMPTY_ARRAY;
            if ($wasReadStored) {
                $storedEntity = $stored->toEntity();
                $entity = $this->toEntity();
            }

            $isContain = Core\Common::isOneArrayContainOther($entity, $storedEntity);

            $result = false;
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

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' UPDATE '
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
                . ' , ' . $this->childColumn
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $nameParameter;

            $record = SqlHandler::writeOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
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
                $isChildSuccess = $this->insertPropertyContent($propertiesId, $propertyKey);
            }

            return $isChildSuccess;
        }

        /** Добавить записи "потомки" дочерней
         * @param array $foreignKeysSet внешние ключи для записей потомков
         * @param string $propertyKey значение внешнего ключа записи информационного свойства
         * @return bool
         */
        private function insertPropertyContent(array $foreignKeysSet, string $propertyKey):bool
        {
            $isChildSuccess = count($foreignKeysSet) > 0;

            $parentValue = $this->id;

            foreach ($foreignKeysSet as $foreignKey) {

                $propertyKeyValue = Core\Common::setIfExists($propertyKey, $foreignKey, self::EMPTY_VALUE);

                $isSuccess = $propertyKeyValue != self::EMPTY_VALUE;
                if ($isSuccess) {
                    $propertyValue = new PropertyContent();

                    $propertyValue->linkToParent = $parentValue;
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
            $wasRubricLoad = $rubric->loadById($this->linkToParent);

            $propertiesId = self::EMPTY_ARRAY;
            if ($wasRubricLoad) {
                $propertiesId = $rubric->readAllPropertyId($propertyKey);
            }

            return $propertiesId;
        }

        /** Получить содержимое свойства по коду свойства
         * @param string $code код свойства
         * @return string идентификатор содержимого
         */
        private function getPositionContentId(string $code):string
        {
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $codeParameter = SqlHandler::setBindParameter(':CODE', $code, \PDO::PARAM_STR);
            $isPropertyHiddenParameter = SqlHandler::setBindParameter(':IS_PROPERTY_HIDDEN',
                InformationProperty::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $resultColumnName = PropertyContent::ID;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . ' C.' . PropertyContent::ID . ' AS ' . $resultColumnName
                . ' FROM '
                . self::TABLE_NAME . ' AS R '
                . ' JOIN ' . self::CONTENT_TABLE_NAME . ' AS C '
                . ' ON R.' . self::ID . ' = C.' . self::CONTENT_LINK

                . ' JOIN ' . InformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = C.' . PropertyContent::PROPERTY

                . ' WHERE '
                . ' R.' . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::IS_HIDDEN . ' = ' . $isPropertyHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isPropertyHiddenParameter;

            $record = SqlHandler::readOneRecord($arguments);

            $result = self::EMPTY_VALUE;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = Core\Common::setIfExists($resultColumnName, $record, self::EMPTY_VALUE);
            }
            return $result;
        }

        /** сохранить содержание свойства
         * @param string $content содержимое свойства
         * @param string $code код свойства
         * @return bool успех выполнения
         */
        public function saveContent(string $content, string $code):bool
        {
            $propertyContentId = $this->getPositionContentId($code);

            $isSuccess = false;
            $propertyContent = new PropertyContent();
            if ($propertyContentId != self::EMPTY_VALUE) {
                $isSuccess = $propertyContent->loadById($propertyContentId);
            }

            if ($isSuccess) {
                $propertyContent->content = $content;
                $isSuccess = $propertyContent->mutateEntity();
            }

            return $isSuccess;
        }

        /** Получить дополнительное значение свойства по коду свойства
         * @param string $code код свойства
         * @param string $redactorId редактор дополнительного значения
         * @return string идентификатор дополнительного значения
         */
        private function getPositionValueId(string $code, string $redactorId):string
        {
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $codeParameter = SqlHandler::setBindParameter(':CODE', $code, \PDO::PARAM_STR);
            $redactorParameter = SqlHandler::setBindParameter(':REDACTOR', $redactorId, \PDO::PARAM_INT);
            $isPropertyHiddenParameter = SqlHandler::setBindParameter(':IS_PROPERTY_HIDDEN',
                InformationProperty::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);
            $isRedactorHiddenParameter = SqlHandler::setBindParameter(':IS_REDACTOR_HIDDEN',
                Redactor::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $resultColumnName = AdditionalValue::ID;

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' V.' . AdditionalValue::ID . ' AS ' . $resultColumnName
                . ' FROM '
                . self::TABLE_NAME . ' AS R '
                . ' JOIN ' . self::CONTENT_TABLE_NAME . ' AS C '
                . ' ON R.' . self::ID . ' = C.' . self::CONTENT_LINK
                . ' JOIN ' . InformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = C.' . PropertyContent::PROPERTY

                . ' JOIN ' . AdditionalValue::TABLE_NAME . ' AS V '
                . ' ON V.' . AdditionalValue::CHILD . ' = C.' . AdditionalValue::PARENT

                . ' JOIN ' . Redactor::TABLE_NAME . ' AS D '
                . ' ON V.' . AdditionalValue::REDACTOR . ' = D.' . Redactor::ID

                . ' WHERE '
                . ' R.' . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::IS_HIDDEN . ' = ' . $isPropertyHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' AND М.' . AdditionalValue::REDACTOR . ' = ' . $redactorParameter[ISqlHandler::PLACEHOLDER]
                . ' AND D.' . Redactor::IS_HIDDEN . ' = ' . $isRedactorHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $redactorParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isPropertyHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isRedactorHiddenParameter;

            $record = SqlHandler::readOneRecord($arguments);

            $result = self::EMPTY_VALUE;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = Core\Common::setIfExists($resultColumnName, $record, self::EMPTY_VALUE);
            }
            return $result;
        }

        /** Проверить допустимость установки дополнительного свойства
         * @param string $code код свойства
         * @return bool результат проверки
         */
        private function mayDefineValue(string $code):bool
        {
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $codeParameter = SqlHandler::setBindParameter(':CODE', $code, \PDO::PARAM_STR);
            $isPropertyHiddenParameter = SqlHandler::setBindParameter(':IS_PROPERTY_HIDDEN',
                InformationProperty::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' NULL '
                . ' FROM '
                . self::TABLE_NAME . ' AS R '
                . ' JOIN ' . self::CONTENT_TABLE_NAME . ' AS C '
                . ' ON R.' . self::ID . ' = C.' . self::CONTENT_LINK
                . ' JOIN ' . InformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = C.' . PropertyContent::PROPERTY

                . ' WHERE '
                . ' R.' . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::IS_HIDDEN . ' = ' . $isPropertyHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isPropertyHiddenParameter;


            $record = SqlHandler::readOneRecord($arguments);

            $result = $record != ISqlHandler::EMPTY_ARRAY;

            return $result;
        }

        /** Сохранить дополнительное значение
         * @param string $value дополнительное значение свойства
         * @param string $code код свойства
         * @param string $redactorId идентификатор редактора
         * @return string идентификатор добавленной позиции
         */
        public function saveValue(string $value, string $code, string $redactorId):string
        {

            $mayDefine = $this->mayDefineValue($code);

            $valueId = self::EMPTY_VALUE;
            if ($mayDefine) {
                $valueId = $this->getPositionValueId($code, $redactorId);
            }

            $isSuccess = $valueId != self::EMPTY_VALUE;

            $positionValue = new AdditionalValue();

            $letAddValue = (!$isSuccess) && $mayDefine;
            if ($letAddValue) {
                $valueId = $this->addPositionValue($code, $redactorId, $positionValue);
            }

            $isSuccess = $valueId != self::EMPTY_VALUE;
            if ($isSuccess) {
                $isSuccess = $positionValue->loadById($valueId);
            }
            if ($isSuccess) {
                $positionValue->value = $value;
                $isSuccess = $positionValue->mutateEntity();
            }

            $id = self::EMPTY_VALUE;
            if ($isSuccess) {
                $id = $positionValue->id;
            }

            return $id;
        }

        /** Добавить дополнительное значение для позиции
         * @param string $code код свойства
         * @param string $redactorId идентификатор редактора
         * @param AdditionalValue $positionValue экземпляр дополнительного значения
         * @return string идентификатор добавленого дополнительного значения
         */
        private function addPositionValue(string $code, string $redactorId, AdditionalValue $positionValue):string
        {
            $propertyContentId = $this->getPositionContentId($code);

            $isSuccess = $propertyContentId != self::EMPTY_VALUE;
            if ($isSuccess) {

                $positionValue->linkToParent = $propertyContentId;
                $positionValue->redactorId = $redactorId;

                $isSuccess = $positionValue->addPredefinedEntity();
            }

            $result = self::EMPTY_VALUE;
            if ($isSuccess) {
                $result = $positionValue->id;
            }

            return $result;
        }

        /** Получить свойства цены доставки
         * @param string $value индекс для дополнительных значений
         * @param string $code индекс для кода свойства
         * @param string $redactor индекс для идентификатора редактора
         * @return array свойства цены
         */
        public function getShippingPricing(
            string $value = AdditionalValue::VALUE,
            string $code = InformationProperty::CODE,
            string $redactor = AdditionalValue::REDACTOR):array
        {
            $settingsRubric = new Rubric();

            $isSuccess = $settingsRubric->loadByCode(self::TRANSPORTATION_CODE);

            $codeIndex = InformationProperty::TABLE_NAME;
            $transportationCodeCollection = SqlHandler::EMPTY_ARRAY;
            if ($isSuccess) {
                $transportationCodeCollection = $settingsRubric->getProperties($codeIndex);
            }

            $isSuccess = $transportationCodeCollection != SqlHandler::EMPTY_ARRAY;
            $record = self::EMPTY_ARRAY;
            if ($isSuccess) {

                $record = $this->getAllPositionValueByCode(
                    $transportationCodeCollection,
                    $codeIndex,
                    $value,
                    $code,
                    $redactor);
            }

            return $record;

        }

        /** Получить свойства цены товара
         * @param string $value индекс для дополнительных значений
         * @param string $code индекс для кода свойства
         * @param string $redactor индекс для идентификатора редактора
         * @return array свойства цены
         */
        public function getGoodsPricing(
            string $value = AdditionalValue::VALUE,
            string $code = InformationProperty::CODE,
            string $redactor = AdditionalValue::REDACTOR):array
        {
            $settingsRubric = new Rubric();

            $isSuccess = $settingsRubric->loadByCode(self::GOODS_PRISING_CODE);

            $codeIndex = InformationProperty::TABLE_NAME;
            $transportationCodeCollection = SqlHandler::EMPTY_ARRAY;
            if ($isSuccess) {
                $transportationCodeCollection = $settingsRubric->getProperties($codeIndex);
            }

            $isSuccess = $transportationCodeCollection != SqlHandler::EMPTY_ARRAY;
            $record = self::EMPTY_ARRAY;
            if ($isSuccess) {

                $record = $this->getAllPositionValueByCode(
                    $transportationCodeCollection,
                    $codeIndex,
                    $value,
                    $code,
                    $redactor);
            }

            return $record;
        }

        /**
         * @param array $transportationCodeCollection
         * @param string $property
         * @param string $valueOutput
         * @param string $codeOutput
         * @param string $redactorOutput
         * @return array
         */
        private function getAllPositionValueByCode(
            array $transportationCodeCollection,
            string $property = InformationProperty::TABLE_NAME,
            string $valueOutput = AdditionalValue::VALUE,
            string $codeOutput = InformationProperty::CODE,
            string $redactorOutput = AdditionalValue::REDACTOR):array
        {
            $arguments = self::EMPTY_ARRAY;
            $inComponentCollection = self::EMPTY_ARRAY;
            foreach ($transportationCodeCollection as $key => $collectionElement) {

                $parameterValue = $collectionElement[$property];
                $parameterName = ":CODE$key";
                $inComponentCollection[] = $parameterName;
                $codeParameter = SqlHandler::setBindParameter($parameterName, $parameterValue, \PDO::PARAM_STR);
                $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            }

            $inComponent = implode(',', $inComponentCollection);

            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_NOT_HIDDEN, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' V.' . AdditionalValue::VALUE . " AS $valueOutput "
                . ' , P.' . InformationProperty::CODE . " AS $codeOutput "
                . ' , V.' . AdditionalValue::REDACTOR . " AS $redactorOutput "
                . ' FROM '
                . $this->tablename . ' AS RP '
                . ' JOIN ' . self::CONTENT_TABLE_NAME . ' AS C '
                . ' ON RP.' . self::ID . ' = C.' . self::CONTENT_LINK
                . ' JOIN ' . InformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = C.' . PropertyContent::PROPERTY

                . ' JOIN ' . AdditionalValue::TABLE_NAME . ' AS V '
                . ' ON V.' . AdditionalValue::CHILD . ' = C.' . AdditionalValue::PARENT

                . ' JOIN ' . Redactor::TABLE_NAME . ' AS R '
                . ' ON R.' . Redactor::ID . ' = V.' . AdditionalValue::REDACTOR

                . ' WHERE '
                . ' RP.' . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::CODE . ' IN (' . $inComponent . ') '
                . ' AND P.' . InformationProperty::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' AND R.' . Redactor::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $record = SqlHandler::readAllRecords($arguments);
            return $record;
        }
    }
}
