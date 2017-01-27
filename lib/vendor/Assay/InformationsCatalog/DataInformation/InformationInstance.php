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
    use Assay\Core\ChildEntity;
    use Assay\DataAccess;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;
    use Assay\InformationsCatalog\StructureInformation\DataType;
    use Assay\InformationsCatalog\StructureInformation\IInformationProperty;
    use Assay\InformationsCatalog\StructureInformation\InformationDomain;
    use Assay\InformationsCatalog\StructureInformation\InformationProperty;
    use Assay\InformationsCatalog\StructureInformation\InformationPropertyDomain;
    use Assay\InformationsCatalog\StructureInformation\Rubric;
    use Assay\InformationsCatalog\StructureInformation\RubricInformationProperty;
    use Assay\InformationsCatalog\StructureInformation\TypeEdit;

    /**
     * Позиция рубрики
     */
    class InformationInstance extends ChildEntity implements IInformationInstance,
        IInstanceUserInformation,
        INamedEntity
    {
        /** @var string имя дочерней таблицы */
        const CHILD_TABLE_NAME = InformationValue::TABLE_NAME;
        /** @var string ссылка на рубрику */
        public $rubricId;

        protected $childTablename = self::CHILD_TABLE_NAME;

        /** Получить свойства цены доставки
         * @return array свойства цены
         */
        public function getShippingPricing():array
        {
        }

        /** Получить свойства цены товара
         * @return array свойства цены
         */
        public function getGoodsPricing():array
        {
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
                                    \string $value = InformationValue::VALUE):array
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
                . ' DT.' . InformationValue::VALUE . 'AS ' . $value

                . ' FROM '
                . InformationInstance::TABLE_NAME . ' AS I '
                . ' JOIN ' . Rubric::TABLE_NAME . ' AS R '
                . ' ON I.' . InformationInstance::RUBRIC . ' = R' . Rubric::ID
                . ' JOIN ' . InformationValue::TABLE_NAME . ' AS V '
                . ' ON I.' . InformationInstance::ID . ' = V' . InformationValue::INSTANCE

                . ' JOIN ' . IInformationProperty::TABLE_NAME . ' AS P '
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
                . ' RP.' . InformationInstance::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
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

            $oneParameter = ISqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . self::ID
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::RUBRIC
                . ' FROM '
                . self::TABLE_NAME
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
                . ' , ' . self::RUBRIC
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

            $stored = new InformationInstance();
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
            $rubricParameter = SqlHandler::setBindParameter(':RUBRIC', $this->rubricId, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' UPDATE '
                . $this->tablename
                . ' SET '
                . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::NAME . ' = ' . $nameParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::DESCRIPTION . ' = ' . $descriptionParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::RUBRIC . ' = ' . $rubricParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' RETURNING '
                . self::ID
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::RUBRIC
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $nameParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $rubricParameter;

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
        public function addChildEntity():bool
        {

            $isSuccess = $this->insertChild();

            $wasRubricLoad = false;
            $rubric = new Rubric();
            if ($isSuccess) {
                $wasRubricLoad = $rubric->loadById($this->rubricId);
            }

            $propertyKey = 'PROPERTY_ID';
            $propertiesId = self::EMPTY_ARRAY;
            if ($wasRubricLoad) {

                $rubric->readAllPropertyId($propertyKey);
            }

            $emptyRecordSet = self::EMPTY_ARRAY;

            $isSuccess = $propertiesId != $emptyRecordSet;
            $foreignKeys = $emptyRecordSet;
            if ($isSuccess) {

                $foreignKeys = $this->setDescendantForeignKey($propertiesId, $propertyKey);
            }

            $isSuccess = $foreignKeys != $emptyRecordSet;
            $isChildSuccess = false;
            if ($isSuccess) {
                $isChildSuccess = $this->insertDescendant($foreignKeys);
            }

            return $isChildSuccess;
        }


        /** Сформировать внешние колючи для таблицы потомков
         * @param array $propertiesId идентификаторы свойсвт
         * @param string $propertyKey индекс для сформированных внешних ключей
         * @return array внешние ключи для дочерней таблицы
         */
        private function setDescendantForeignKey(array $propertiesId, string $propertyKey = InformationProperty::EXTERNAL_ID)
        {
            $instanceForeignKey = DataAccess\Common::setForeignKeyParameter(InformationInstance::EXTERNAL_ID,
                $this->id);

            $foreignKeys = self::EMPTY_ARRAY;
            foreach ($propertiesId as $propertyId) {
                $propertyForeignKey = DataAccess\Common::setForeignKeyParameter(InformationValue::PROPERTY,
                    $propertyId[$propertyKey]);

                $foreignKeys [] = array($instanceForeignKey, $propertyForeignKey,);
            }

            return $foreignKeys;
        }

        /** Добавить Дочернюю запись
         * @return bool успех выполнения
         */
        private function insertChild()
        {
            $codeParameter = SqlHandler::setBindParameter(':CODE', $this->code, \PDO::PARAM_STR);
            $descriptionParameter = SqlHandler::setBindParameter(':DESCRIPTION', $this->description, \PDO::PARAM_STR);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);
            $nameParameter = SqlHandler::setBindParameter(':NAME', $this->name, \PDO::PARAM_STR);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'INSERT INTO  ' . $this->tablename
                . ' ('
                . self::IS_HIDDEN
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ')'
                . ' VALUES  ('
                . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . $nameParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . $descriptionParameter[ISqlHandler::PLACEHOLDER]
                . ')'
                . ' RETURNING '
                . self::ID
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::CODE
                . ' , ' . self::NAME
                . ' , ' . self::DESCRIPTION
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $nameParameter;

            $parent = SqlHandler::writeOneRecord($arguments);

            $emptyRecordSet = ISqlHandler::EMPTY_ARRAY;

            $isSuccess = $parent != $emptyRecordSet;
            if ($isSuccess) {
                $isSuccess = $this->setByNamedValue($parent);
            }

            return $isSuccess;
        }

        /** Добавить записи "потомки" дочерней
         * @param array $foreignKeys внешние ключи для записей потомков
         * @return bool
         */
        private function insertDescendant(array $foreignKeys):bool
        {
            $isChildSuccess = true;

            foreach ($foreignKeys as $foreignKeySet) {
                $columnNames = DataAccess\Common::getNameString($foreignKeySet);
                $columnValues = DataAccess\Common::getIntegerValue($foreignKeySet);

                $columnNamesInsideParentheses = "($columnNames)";
                $columnValuesInsideParentheses = "($columnValues)";

                $arguments[ISqlHandler::QUERY_TEXT] =
                    ' INSERT INTO ' . $this->childTablename . "$columnNamesInsideParentheses 
VALUES $columnValuesInsideParentheses 
 RETURNING $columnNames" . DataAccess\ICommon::ENUMERATION_SEPARATOR . self::ID
                    . ' ; ';

                $child = SqlHandler::writeOneRecord($arguments);

                $isChildSuccess = $child != self::EMPTY_ARRAY && $isChildSuccess;
            }
            return $isChildSuccess;
        }

    }
}
