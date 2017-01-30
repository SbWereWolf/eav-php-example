<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:11
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\Common;
    use Assay\Core\NamedEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;
    use Assay\InformationsCatalog\DataInformation\IRubricPosition;
    use Assay\InformationsCatalog\DataInformation\RubricPosition;

    /**
     * Рубрика каталога
     */
    class Rubric extends NamedEntity implements IRubric
    {
        /** @var string имя таблицы для хранения сущности */
        const TABLE_NAME = 'rubric';

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
                ' SELECT 
                ' . self::CODE . ' AS ' . $codeKey
                . ' FROM '
                . IRubricPosition::TABLE_NAME
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
         * @return array параметры поиска
         */
        public function getSearchParameters():array
        {
        }
        /** Считать все идентификаторы свойств рубрики
         * @param $propertyKey string индекс для идентификатора свойства
         * @return array идентификаторы свойств рубрики
         */
        public function readAllPropertyId( string $propertyKey = InformationProperty::EXTERNAL_ID)
        {
            $rubricParameter = SqlHandler::setBindParameter(':RUBRIC', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN',
                self::DEFINE_AS_NOT_HIDDEN,
                \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                . ' P.' . InformationProperty::ID . ' AS ' . $propertyKey
                . ' FROM '
                . RubricInformationProperty::TABLE_NAME . ' AS RP '
                . ' JOIN ' . IInformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = RP.' . RubricInformationProperty::PROPERTY
                . ' WHERE '
                . ' RP.' . RubricInformationProperty::RUBRIC . ' = ' . $rubricParameter[ISqlHandler::PLACEHOLDER]
                . ' AND P.' . InformationProperty::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $rubricParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $result = SqlHandler::readAllRecords($arguments);

            return $result;
        }

        /** Получить свойства рубрики
         * @param string $property индекс для кода рубрики
         * @param string $typeEdit индекс для кода типа редактирования
         * @param string $searchType индекс для кода типа поиска
         * @param string $dataType индекс для кода типа данных
         * @return array свойства рубрики
         */
        public function getProperties(string $property = IInformationProperty::TABLE_NAME,
                                      string $typeEdit= TypeEdit::TABLE_NAME,
                                      string $searchType= SearchType::TABLE_NAME,
                                      string $dataType= DataType::TABLE_NAME):array
        {
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_NOT_HIDDEN, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' SELECT '
                .' P.'.InformationProperty::CODE . ' AS '.$property
                .' TE.'.TypeEdit::CODE. 'AS '.$typeEdit
                .' ST.'.SearchType::CODE. 'AS '.$searchType
                .' DT.'.DataType::CODE. 'AS '.$dataType
                
                . ' FROM '
                . RubricInformationProperty::TABLE_NAME . ' AS RP '
                . ' JOIN ' . IInformationProperty::TABLE_NAME . ' AS P '
                . ' ON P.' . InformationProperty::ID . ' = RP.' . RubricInformationProperty::PROPERTY
                . ' JOIN ' . InformationPropertyDomain::TABLE_NAME . ' AS PD '
                . ' ON P.' . InformationProperty::ID . ' = PD.' . InformationProperty::EXTERNAL_ID
                . ' JOIN ' . InformationDomain::TABLE_NAME.' AS D '
                . ' ON D.' . InformationDomain::ID . ' = PD.' . InformationDomain::EXTERNAL_ID

                . ' JOIN ' . TypeEdit::TABLE_NAME.' AS TE '
                . ' ON D.' . InformationDomain::TYPE_EDIT . ' = TE.' . TypeEdit::ID
                . ' JOIN ' . SearchType::TABLE_NAME.' AS ST '
                . ' ON D.' . InformationDomain::SEARCH_TYPE . ' = ST.' . SearchType::ID
                . ' JOIN ' . DataType::TABLE_NAME.' AS DT '
                . ' ON D.' . InformationDomain::DATA_TYPE . ' = DT.' . DataType::ID

                . ' WHERE '
                . ' RP.' . RubricInformationProperty::RUBRIC . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
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
    }
}
