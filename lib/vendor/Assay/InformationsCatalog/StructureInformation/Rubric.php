<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:11
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\Common;
    use Assay\Core\ICommon;
    use Assay\Core\NamedEntity;
    use Assay\DataAccess;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;
    use Assay\InformationsCatalog\DataInformation\DigitalContent;
    use Assay\InformationsCatalog\DataInformation\PropertyContent;
    use Assay\InformationsCatalog\DataInformation\RubricPosition;
    use Assay\InformationsCatalog\DataInformation\StringContent;

    /**
     * Рубрика каталога
     */
    class Rubric extends NamedEntity implements IRubric
    {
        /** @var string имя таблицы для хранения сущности */
        const TABLE_NAME = 'rubric';

        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'rubric_id';

        /** @var string имя таблицы для хранения сущности */
        protected $tablename = self::TABLE_NAME;

        /** Получить описания позиций рубрики
         * @param string $codeKey индекс для элементов массива
         * @return array позиции
         */
        public function getMap(string $codeKey = RubricPosition::CODE):array
        {
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_NOT_HIDDEN, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' btrim(' . RubricPosition::CODE . ') AS "' . $codeKey . '"'
                . ' FROM '
                . RubricPosition::TABLE_NAME
                . ' WHERE '
                . self::EXTERNAL_ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND ' . RubricPosition::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER] .
                ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $result = SqlHandler::readAllRecords($arguments);

            return $result;
        }

        /** Получить параметры поиска по рубрике
         * @param string $searchIndex индекс для параметров поиска
         * @return array параметры поиска
         */
        public function getSearchParameters(string $searchIndex = Rubric::DESCRIPTION):array
        {

            $propertyKey = InformationProperty::EXTERNAL_ID;
            $rubricProperties = $this->readAllPropertyId($propertyKey);

            $isSuccess = is_array($rubricProperties);
            $result = self::EMPTY_ARRAY;
            if ($isSuccess) {
                $result = $this->getRubricPropertySearchOptions($rubricProperties);
            }

            return $result;
        }

        private function getPropertyEnumeration(
            string $propertyId, $dataTypeCode):array
        {
            $enumerationIndex = PropertyContent::TABLE_NAME;

            $propertyIdParameter = SqlHandler::setBindParameter(':PROPERTY_ID', $propertyId, \PDO::PARAM_INT);
            $rubricIdParameter = SqlHandler::setBindParameter(':RUBRIC_ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',
                self::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $queryText = self::EMPTY_VALUE;

            switch ($dataTypeCode) {
                case DataType::STRING:
                    $queryText =
                        ' SELECT '
                        . ' SC.' . StringContent::STRING . ' AS "' . $enumerationIndex . '"'
                        . ' FROM '
                        . $this->tablename . ' AS R '
                        . ' JOIN ' . RubricPosition::TABLE_NAME . ' AS RP '
                        . ' ON RP.' . RubricPosition::CHILD . ' = R.' . RubricPosition::PARENT
                        . ' JOIN ' . RubricPosition::CONTENT_TABLE_NAME . ' AS C '
                        . ' ON RP.' . RubricPosition::ID . ' = C.' . RubricPosition::CONTENT_LINK

                        . ' JOIN ' . StringContent::TABLE_NAME . ' AS SC '
                        . ' ON SC.' . StringContent::CHILD . ' = C.' . StringContent::PARENT

                        . ' WHERE '
                        . ' R.' . Rubric::ID . ' = ' . $rubricIdParameter[ISqlHandler::PLACEHOLDER]
                        . ' AND C.' . PropertyContent::PROPERTY . ' = ' . $propertyIdParameter[ISqlHandler::PLACEHOLDER]
                        . ' AND RP.' . RubricPosition::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                        . ' GROUP BY SC.' . StringContent::STRING
                        . ';';
                    break;
                case DataType::DIGITAL:
                    $queryText =
                        ' SELECT '
                        . ' DC.' . DigitalContent::DIGITAL . ' AS "' . $enumerationIndex . '"'
                        . ' FROM '
                        . $this->tablename . ' AS R '
                        . ' JOIN ' . RubricPosition::TABLE_NAME . ' AS RP '
                        . ' ON RP.' . RubricPosition::CHILD . ' = R.' . RubricPosition::PARENT
                        . ' JOIN ' . RubricPosition::CONTENT_TABLE_NAME . ' AS C '
                        . ' ON RP.' . RubricPosition::ID . ' = C.' . RubricPosition::CONTENT_LINK

                        . ' JOIN ' . DigitalContent::TABLE_NAME . ' AS DC '
                        . ' ON DC.' . DigitalContent::CHILD . ' = C.' . DigitalContent::PARENT

                        . ' WHERE '
                        . ' R.' . Rubric::ID . ' = ' . $rubricIdParameter[ISqlHandler::PLACEHOLDER]
                        . ' AND C.' . PropertyContent::PROPERTY . ' = ' . $propertyIdParameter[ISqlHandler::PLACEHOLDER]
                        . ' AND RP.' . RubricPosition::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                        . ' GROUP BY DC.' . DigitalContent::DIGITAL
                        . ';';
                    break;
            }

            $arguments[ISqlHandler::QUERY_TEXT] = $queryText;

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $rubricIdParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $propertyIdParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $result = SqlHandler::readAllRecords($arguments);

            return $result;
        }

        private function getPropertyBetween(string $propertyId):array
        {

            $maximumValueIndex = DataAccess\ICommon::MAXIMUM;
            $minimumValueIndex = DataAccess\ICommon::MINIMUM;

            $propertyIdParameter = SqlHandler::setBindParameter(':PROPERTY_ID', $propertyId, \PDO::PARAM_INT);
            $rubricIdParameter = SqlHandler::setBindParameter(':RUBRIC_ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',
                self::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' MAX(DC.' . DigitalContent::DIGITAL . ') AS "' . $maximumValueIndex . '"'
                . ' , MIN(DC.' . DigitalContent::DIGITAL . ') AS "' . $minimumValueIndex . '"'
                . ' FROM '
                . $this->tablename . ' AS R '
                . ' JOIN ' . RubricPosition::TABLE_NAME . ' AS RP '
                . ' ON RP.' . RubricPosition::CHILD . ' = R.' . RubricPosition::PARENT
                . ' JOIN ' . RubricPosition::CONTENT_TABLE_NAME . ' AS C '
                . ' ON RP.' . RubricPosition::ID . ' = C.' . RubricPosition::CONTENT_LINK

                . ' JOIN ' . DigitalContent::TABLE_NAME . ' AS DC '
                . ' ON DC.' . DigitalContent::CHILD . ' = C.' . DigitalContent::PARENT

                . ' WHERE '
                . ' R.' . Rubric::ID . ' = ' . $rubricIdParameter[ISqlHandler::PLACEHOLDER]
                . ' AND C.' . PropertyContent::PROPERTY . ' = ' . $propertyIdParameter[ISqlHandler::PLACEHOLDER]
                . ' AND RP.' . RubricPosition::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $propertyIdParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $rubricIdParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $result = SqlHandler::readAllRecords($arguments);

            return $result;
        }

        /** Считать все идентификаторы свойств рубрики
         * @param $propertyKey string индекс для идентификатора свойства
         * @return array идентификаторы свойств рубрики
         */
        public function readAllPropertyId(string $propertyKey = InformationProperty::EXTERNAL_ID)
        {
            $rubricParameter = SqlHandler::setBindParameter(':RUBRIC', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',
                self::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' P.' . InformationProperty::ID . ' AS "' . $propertyKey . '"'
                . ' FROM '
                . RubricInformationProperty::TABLE_NAME . ' AS RP '
                . ' JOIN ' . InformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = RP.' . RubricInformationProperty::RIGHT
                . ' WHERE '
                . ' RP.' . RubricInformationProperty::LEFT . ' = ' . $rubricParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $rubricParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $result = SqlHandler::readAllRecords($arguments);

            return $result;
        }

        /** Получить свойства рубрики
         * @param string $property индекс для кода рубрики
         * @param string $dataType индекс для кода типа данных
         * @param string $typeEdit индекс для кода типа редактирования
         * @param string $searchType индекс для кода типа поиска
         * @return array свойства рубрики
         */
        public function getProperties(
            string $property = InformationProperty::TABLE_NAME,
            string $dataType = DataType::TABLE_NAME,
            string $typeEdit = TypeEdit::TABLE_NAME,
            string $searchType = SearchType::TABLE_NAME):array
        {
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_NOT_HIDDEN, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' btrim(P.' . InformationProperty::CODE . ') AS "' . $property . '"'
                . ' , btrim(TE.' . TypeEdit::CODE . ') AS "' . $typeEdit . '"'
                . ' , btrim(ST.' . SearchType::CODE . ') AS "' . $searchType . '"'
                . ' , btrim(DT.' . DataType::CODE . ') AS "' . $dataType . '"'

                . ' FROM '
                . RubricInformationProperty::TABLE_NAME . ' AS RP '
                . ' JOIN ' . InformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = RP.' . RubricInformationProperty::RIGHT
                . ' JOIN ' . DomainInformationProperty::TABLE_NAME . ' AS PD '
                . ' ON P.' . InformationProperty::ID . ' = PD.' . InformationProperty::EXTERNAL_ID
                . ' JOIN ' . InformationDomain::TABLE_NAME . ' AS D '
                . ' ON D.' . InformationDomain::ID . ' = PD.' . InformationDomain::EXTERNAL_ID

                . ' JOIN ' . TypeEdit::TABLE_NAME . ' AS TE '
                . ' ON D.' . InformationDomain::TYPE_EDIT . ' = TE.' . TypeEdit::ID
                . ' JOIN ' . SearchType::TABLE_NAME . ' AS ST '
                . ' ON D.' . InformationDomain::SEARCH_TYPE . ' = ST.' . SearchType::ID
                . ' JOIN ' . DataType::TABLE_NAME . ' AS DT '
                . ' ON D.' . InformationDomain::DATA_TYPE . ' = DT.' . DataType::ID

                . ' WHERE '
                . ' RP.' . RubricInformationProperty::LEFT . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' AND D.' . InformationDomain::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER] .
                ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $result = SqlHandler::readAllRecords($arguments);

            return $result;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool
        {
            $result = false;

            $stored = new Rubric();
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

        /** Добавить позицию
         * @return string идентификатор добавленной позиции
         */
        public function addPosition():string
        {
            $position = new RubricPosition();

            $position->linkToParent = $this->id;
            $position->addPredefinedEntity();

            $id = $position->id;

            return $id;
        }

        /** Добавить свойство
         * @param string $code код свойства
         * @return bool успех выполнения
         */
        public function addProperty(string $code):bool
        {
            $property = new InformationProperty();

            $isSuccess = $property->loadByCode($code);

            if ($isSuccess) {
                $linkage = new RubricInformationProperty();
                $isSuccess = $linkage->addInnerLinkage($this->id, $property->id);
            }

            return $isSuccess;
        }

        /** Скрыть свойство
         * @param string $code код свойства
         * @return bool успех выполнения
         */
        public function dropProperty(string $code):bool
        {

            $property = new InformationProperty();
            $isSuccess = $property->loadByCode($code);

            if ($isSuccess) {
                $link = new RubricInformationProperty();
                $isSuccess = $link->dropLinkageByBoth($this->id, $property->id);
            }

            return $isSuccess;
        }

        /** Выполнить поиск
         * @param array $filterProperties параметры поиска
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public function search(array $filterProperties, int $start = 0, int $paging = 0):array
        {
            $arguments = self::EMPTY_ARRAY;

            $filterProperties = $filterProperties[Rubric::DESCRIPTION];

            $stringSearchCondition = $this->setStringSearchCondition($filterProperties, $arguments);
            $digitalSearchCondition = $this->setDigitalSearchCondition($filterProperties, $arguments);

            $letAddStringSearch = $stringSearchCondition != self::EMPTY_VALUE;
            $letAddDigitalSearch = $digitalSearchCondition != self::EMPTY_VALUE;

            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',
                self::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $searchRequestPrefix = ' SELECT '
                . ' DISTINCT btrim(RP.' . RubricPosition::CODE . ') AS "' . RubricPosition::CODE . '"'
                . ' FROM '
                . $this->tablename . ' AS R '
                . ' JOIN ' . RubricPosition::TABLE_NAME . ' AS RP '
                . ' ON RP.' . RubricPosition::CHILD . ' = R.' . RubricPosition::PARENT
                . ' JOIN ' . RubricPosition::CONTENT_TABLE_NAME . ' AS C '
                . ' ON RP.' . RubricPosition::ID . ' = C.' . RubricPosition::CONTENT_LINK;

            $searchStringParagraph =
                ' JOIN ' . StringContent::TABLE_NAME . ' AS SC '
                . ' ON SC.' . StringContent::CHILD . ' = C.' . StringContent::PARENT;

            $searchDigitalParagraph =
                ' JOIN ' . DigitalContent::TABLE_NAME . ' AS DC '
                . ' ON DC.' . DigitalContent::CHILD . ' = C.' . DigitalContent::PARENT;

            $searchRequestSuffix =

                ' JOIN ' . RubricInformationProperty::TABLE_NAME . ' AS RI '
                . ' ON R.' . Rubric::ID . ' = RI.' . RubricInformationProperty::LEFT
                . ' JOIN ' . InformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = RI.' . RubricInformationProperty::RIGHT
                . ' AND P.' . InformationProperty::ID . ' = C.' . PropertyContent::PROPERTY

                . ' WHERE '
                . ' R.' . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . RubricPosition::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER];

            $searchRequest = $searchRequestPrefix;

            if ($letAddStringSearch && !$letAddDigitalSearch) {
                $searchRequest .= $searchStringParagraph . $searchRequestSuffix . ' AND ' . $stringSearchCondition;
            }

            if ($letAddStringSearch && $letAddDigitalSearch) {
                $searchRequest .= $searchStringParagraph . $searchRequestSuffix . ' AND ' . $stringSearchCondition
                    . ' INTERSECT ' . $searchRequestPrefix
                    . $searchDigitalParagraph . $searchRequestSuffix . ' AND ' . $digitalSearchCondition;
            }

            if (!$letAddStringSearch && $letAddDigitalSearch) {
                $searchRequest .= $searchDigitalParagraph . $searchRequestSuffix . ' AND ' . $digitalSearchCondition;
            }

            if (!$letAddStringSearch && !$letAddDigitalSearch) {
                $searchRequest .= $searchRequestSuffix;
            }

            $pagingString = SqlHandler::getPagingCondition($start, $paging);

            $searchRequest .= $pagingString;

            $arguments[ISqlHandler::QUERY_TEXT] = $searchRequest;

            $searchResult = SqlHandler::readAllRecords($arguments);

            return $searchResult;
        }


        /** Получить параметры поиска по колонке свойства
         * @param string $propertyId идентификатор свойства
         * @return array параметры поиска по колонке свойства
         */
        private function getPropertySearchOptions(
            string $propertyId
        )
        {
            $property = new InformationProperty();
            $property->loadById($propertyId);

            $searchType = $property->getPropertySearchType();

            $dataType = self::EMPTY_OBJECT;
            $isSuccess = $searchType != self::EMPTY_OBJECT && $searchType->id != SearchType::EMPTY_VALUE;
            if ($isSuccess) {
                $dataType = $property->getPropertyDataType();
            }

            $searchParameters = self::EMPTY_ARRAY;
            $isSuccess = $dataType != self::EMPTY_OBJECT
                && $dataType->id != DataType::EMPTY_VALUE
                && $dataType->isHidden == $dataType::DEFINE_AS_NOT_HIDDEN;
            if ($isSuccess) {

                $dataTypeCode = $dataType->code;

                $searchTypeIndex = SearchType::TABLE_NAME;
                $searchParameterIndex = SearchType::DESCRIPTION;
                $dataTypeIndex = DataType::TABLE_NAME;

                $searchTypeCode = $searchType->code;
                switch ($searchTypeCode) {
                    case SearchType::ENUMERATION :
                        $searchParameters [$searchTypeIndex] = SearchType::ENUMERATION;
                        $propertyEnumeration = $this->getPropertyEnumeration($propertyId, $dataTypeCode);
                        $searchParameters [$searchParameterIndex] = $propertyEnumeration;
                        break;
                    case SearchType::BETWEEN :
                        $searchParameters [$searchTypeIndex] = SearchType::BETWEEN;
                        $propertyBetween = $this->getPropertyBetween($propertyId);
                        $searchParameters [$searchParameterIndex] = $propertyBetween;
                        break;
                    case SearchType::LIKE :
                        $searchParameters [$searchTypeIndex] = SearchType::LIKE;
                        $searchParameters [$searchParameterIndex] = self::EMPTY_ARRAY;
                        break;
                }

                if ($searchParameters != self::EMPTY_ARRAY) {
                    $searchParameters [$dataTypeIndex] = $dataTypeCode;
                }

            }
            return $searchParameters;
        }

        /** Получить параметры поиска по всем свойствам рубрики
         * @param array $rubricProperties идентификаторы свойств рубрики
         * @param string $searchIndex индекс для параметров поиска
         * @return array массив параметров поиска
         */
        private function getRubricPropertySearchOptions(
            array $rubricProperties,
            string $searchIndex = Rubric::DESCRIPTION
        ):array
        {

            $propertyKey = InformationProperty::EXTERNAL_ID;

            $result = self::EMPTY_ARRAY;
            foreach ($rubricProperties as $key => $rubricProperty) {

                $propertyId = Common::setIfExists($propertyKey, $rubricProperty, self::EMPTY_VALUE);

                $isSuccess = $propertyId != self::EMPTY_VALUE;
                $searchParameters = self::EMPTY_VALUE;
                if ($isSuccess) {
                    $searchParameters = $this->getPropertySearchOptions($propertyId);
                }
                $isSuccess = $searchParameters != self::EMPTY_ARRAY;
                $property = new InformationProperty();
                if ($isSuccess) {
                    $isSuccess = $property->loadById($propertyId);
                }
                if ($isSuccess) {
                    $code = $property->code;
                    $result[$searchIndex][$code] = $searchParameters;
                }
            }
            return $result;
        }

        /**
         * @param $propertyCode
         * @param $filterProperty
         * @param $arguments
         * @return string
         */
        private function setBetweenCondition($propertyCode, $filterProperty, &$arguments):string
        {

            $searchIndex = Rubric::DESCRIPTION; // массив настроек для всех свойств
            $maximumValueIndex = DataAccess\ICommon::MAXIMUM; // максимум
            $minimumValueIndex = DataAccess\ICommon::MINIMUM; // минимум
            $searchTypeIndex = SearchType::TABLE_NAME; // тип поиска

            $operatorType = $filterProperty[$searchTypeIndex];
            $parameterCode = str_replace(' ', '', $propertyCode . $operatorType);
            $searchParameters = $filterProperty[$searchIndex];

            $betweenParameter = Common::setIfExists(ICommon::FIRST_INDEX, $searchParameters, self::EMPTY_ARRAY);
            $maximum = Common::setIfExists($maximumValueIndex, $betweenParameter, self::EMPTY_VALUE);
            $minimum = Common::setIfExists($minimumValueIndex, $betweenParameter, self::EMPTY_VALUE);

            $isSuccess = $maximum != self::EMPTY_VALUE && $minimum != self::EMPTY_VALUE;
            $condition = self::EMPTY_VALUE;
            if ($isSuccess) {
                $maximumParameterName = ":$parameterCode$maximumValueIndex";
                $minimumParameterName = ":$parameterCode$minimumValueIndex";

                $maximumCodeParameter = SqlHandler::setBindParameter($maximumParameterName,
                    $maximum,
                    \PDO::PARAM_STR);
                $minimumCodeParameter = SqlHandler::setBindParameter($minimumParameterName,
                    $minimum,
                    \PDO::PARAM_STR);
                $parameterName = ":$parameterCode";
                $codeParameter = SqlHandler::setBindParameter($parameterName, $propertyCode, \PDO::PARAM_STR);
                $arguments[ISqlHandler::QUERY_PARAMETER][] = $maximumCodeParameter;
                $arguments[ISqlHandler::QUERY_PARAMETER][] = $minimumCodeParameter;
                $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;

                $condition = ' P.' . InformationProperty::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                    . ' AND C.' . PropertyContent::CONTENT
                    . ' BETWEEN ' . $minimumCodeParameter[ISqlHandler::PLACEHOLDER]
                    . ' AND ' . $maximumCodeParameter[ISqlHandler::PLACEHOLDER];
            }
            return $condition;
        }

        /**
         * @param $propertyCode
         * @param $filterProperty
         * @param $arguments
         * @return string
         */
        private function setStringEnumerationCondition($propertyCode, $filterProperty, &$arguments):string
        {
            $searchTypeIndex = SearchType::TABLE_NAME; // тип поиска
            $searchIndex = Rubric::DESCRIPTION; // массив настроек для всех свойств
            $enumerationIndex = PropertyContent::TABLE_NAME; // перечисление

            $operatorType = $filterProperty[$searchTypeIndex];
            $parameterCode = str_replace(' ', '', $propertyCode . $operatorType);
            $searchParameters = $filterProperty[$searchIndex];

            $inComponentCollection = self::EMPTY_ARRAY;
            foreach ($searchParameters as $index => $collectionElement) {

                $parameterValue = $collectionElement[$enumerationIndex];
                $parameterName = ":$parameterCode$index";
                $inComponentCollection[] = $parameterName;
                $codeParameter = SqlHandler::setBindParameter($parameterName, $parameterValue, \PDO::PARAM_STR);
                $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            }

            $parameterName = ":$parameterCode";
            $codeParameter = SqlHandler::setBindParameter($parameterName, $propertyCode, \PDO::PARAM_STR);
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;

            $inComponent = implode(',', $inComponentCollection);
            $condition = ' P.' . InformationProperty::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' AND SC.' . StringContent::STRING . " IN ($inComponent) ";
            return $condition;
        }

        /**
         * @param $propertyCode
         * @param $filterProperty
         * @param $arguments
         * @return string
         */
        private function setDigitalEnumerationCondition($propertyCode, $filterProperty, &$arguments):string
        {
            $searchTypeIndex = SearchType::TABLE_NAME; // тип поиска
            $searchIndex = Rubric::DESCRIPTION; // массив настроек для всех свойств
            $enumerationIndex = PropertyContent::TABLE_NAME; // перечисление

            $operatorType = $filterProperty[$searchTypeIndex];
            $parameterCode = str_replace(' ', '', $propertyCode . $operatorType);
            $searchParameters = $filterProperty[$searchIndex];

            $inComponentCollection = self::EMPTY_ARRAY;
            foreach ($searchParameters as $index => $collectionElement) {

                $parameterValue = $collectionElement[$enumerationIndex];
                $parameterName = "(:$parameterCode$index)::numeric";
                $inComponentCollection[] = $parameterName;
                $codeParameter = SqlHandler::setBindParameter($parameterName, $parameterValue, \PDO::PARAM_STR);
                $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            }

            $parameterName = ":$parameterCode";
            $codeParameter = SqlHandler::setBindParameter($parameterName, $propertyCode, \PDO::PARAM_STR);
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;

            $inComponent = implode(',', $inComponentCollection);
            $condition = ' P.' . InformationProperty::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' AND SC.' . StringContent::STRING . " IN ($inComponent) ";
            return $condition;
        }

        /**
         * @param $propertyCode
         * @param $filterProperty
         * @param $arguments
         * @return string
         */
        private function setLikeCondition($propertyCode, $filterProperty, &$arguments):string
        {
            $searchTypeIndex = SearchType::TABLE_NAME; // тип поиска
            $searchIndex = Rubric::DESCRIPTION; // массив настроек для всех свойств


            $operatorType = $filterProperty[$searchTypeIndex];
            $searchParameters = $filterProperty[$searchIndex];
            $parameterCode = str_replace(' ', '', $propertyCode . $operatorType);

            $like = Common::setIfExists(ICommon::FIRST_INDEX, $searchParameters, self::EMPTY_ARRAY);

            $isSuccess = $like != self::EMPTY_VALUE;
            $condition = self::EMPTY_VALUE;
            if ($isSuccess) {
                $parameterCode = ":$parameterCode";
                $parameterLike = ':LIKE_VALUE';

                $codeParameter = SqlHandler::setBindParameter($parameterCode, $propertyCode, \PDO::PARAM_STR);
                $likeParameter = SqlHandler::setBindParameter($parameterLike, $like, \PDO::PARAM_STR);

                $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
                $arguments[ISqlHandler::QUERY_PARAMETER][] = $likeParameter;

                $condition = ' P.' . InformationProperty::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                    . ' AND SC.' . StringContent::STRING . ' LIKE \'%\'||' . $likeParameter[ISqlHandler::PLACEHOLDER] . '||\'%\' ';
            }

            return $condition;
        }

        /**
         * @param array $filterProperties
         * @param array $arguments
         * @return string
         */
        private function setStringSearchCondition(array $filterProperties, array &$arguments):string
        {
            $conditionCollection = self::EMPTY_ARRAY;
            foreach ($filterProperties as $code => $filterProperty) {

                $searchTypeIndex = SearchType::TABLE_NAME; // тип поиска
                $operatorType = $filterProperty[$searchTypeIndex];

                $dataTypeIndex = DataType::TABLE_NAME;
                $dataType = $filterProperty[$dataTypeIndex];


                $isStringData = $dataType == DataType::STRING;
                if ($isStringData) {

                    /* ?? */
                    $propertyCode = trim($code);
                    $condition = self::EMPTY_VALUE;
                    switch ($operatorType) {
                        case SearchType::ENUMERATION :
                            $condition = $this->setStringEnumerationCondition($propertyCode, $filterProperty, $arguments);
                            break;
                        case SearchType::LIKE :
                            $condition = $this->setLikeCondition($propertyCode, $filterProperty, $arguments);
                            break;
                    }

                    $isSuccess = $condition != self::EMPTY_VALUE;
                    if ($isSuccess) {
                        $conditionCollection[] = $condition;
                    }
                }

            }

            $conditionGlueOperator = ' AND ';
            $conditionGluePrefix = $conditionGlueOperator . '( ';
            $conditionGlueSuffix = ' ) ';
            $searchCondition = self::EMPTY_VALUE;

            $isArray = is_array($conditionCollection);
            if ($isArray) {
                foreach ($conditionCollection as $propertyCondition) {
                    $searchCondition .= $conditionGluePrefix . $propertyCondition . $conditionGlueSuffix;
                }
            }

            $searchCondition = preg_replace('/' . $conditionGlueOperator . '/', '', $searchCondition, 1);
            return $searchCondition;
        }

        /**
         * @param array $filterProperties
         * @param array $arguments
         * @return string
         */
        private function setDigitalSearchCondition(array $filterProperties, array &$arguments):string
        {
            $conditionCollection = self::EMPTY_ARRAY;
            foreach ($filterProperties as $code => $filterProperty) {

                $searchTypeIndex = SearchType::TABLE_NAME; // тип поиска
                $operatorType = $filterProperty[$searchTypeIndex];

                $dataTypeIndex = DataType::TABLE_NAME;
                $dataType = $filterProperty[$dataTypeIndex];


                $isStringData = $dataType == DataType::STRING;
                if ($isStringData) {

                    /* ?? */
                    $propertyCode = trim($code);
                    $condition = self::EMPTY_VALUE;
                    switch ($operatorType) {
                        case SearchType::ENUMERATION :
                            $condition = $this->setDigitalEnumerationCondition($propertyCode, $filterProperty, $arguments);
                            break;
                        case SearchType::BETWEEN :
                            $condition = $this->setBetweenCondition($propertyCode, $filterProperty, $arguments);
                            break;
                    }

                    $isSuccess = $condition != self::EMPTY_VALUE;
                    if ($isSuccess) {
                        $conditionCollection[] = $condition;
                    }
                }

            }

            $conditionGlueOperator = ' AND ';
            $conditionGluePrefix = $conditionGlueOperator . '( ';
            $conditionGlueSuffix = ' ) ';
            $searchCondition = self::EMPTY_VALUE;

            $isArray = is_array($conditionCollection);
            if ($isArray) {
                foreach ($conditionCollection as $propertyCondition) {
                    $searchCondition .= $conditionGluePrefix . $propertyCondition . $conditionGlueSuffix;
                }
            }

            $searchCondition = preg_replace('/' . $conditionGlueOperator . '/', '', $searchCondition, 1);
            return $searchCondition;
        }
    }
}
