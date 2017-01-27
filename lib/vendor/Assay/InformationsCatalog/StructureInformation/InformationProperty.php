<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:13
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\Common;
    use Assay\Core\NamedEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    /**
     * Свойство рубрики
     */
    class InformationProperty extends NamedEntity implements IInformationProperty
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'information_property_id';

        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;

        private $informationDomain = self::EMPTY_VALUE;

        /** Загрузить значения свойств по коду сущности
         * @param string $code код сущности
         * @return bool успех выполнения
         */
        public function loadByCode(string $code):bool
        {

            $isSuccessfulByCode = parent::loadByCode($code);
            $isSuccessfulLoadDomain = $this->loadInformationDomain();

            $result = $isSuccessfulByCode  && $isSuccessfulLoadDomain  ;

            return $result;
        }

        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return bool успех выполнения
         */
        public function loadById(string $id):bool
        {
            $isSuccessfulById = parent::loadById($id);
            $isSuccessfulLoadDomain = $this->loadInformationDomain();

            $result = $isSuccessfulById  && $isSuccessfulLoadDomain  ;

            return $result;
        }

        /** Установить свойства в соответствии со значениями элементов массива
         * @param array $namedValue массив с именованными элементами
         * @return bool успех выполнения
         */
        public function setByNamedValue(array $namedValue):bool
        {
            parent::setByNamedValue($namedValue);

            $this->informationDomain = Common::setIfExists(self::INFORMATION_DOMAIN, $namedValue, self::EMPTY_VALUE);

            return true;
        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {

            $result = parent::toEntity();

            $result [self::INFORMATION_DOMAIN] = $this->informationDomain;

            return $result;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успех выполнения
         */
        public function mutateEntity():bool
        {
            $updateResult = false;

            $stored = new InformationProperty();
            $wasReadStored = $stored->loadById($this->id);

            $storedEntity = array();
            $entity = array();
            if ($wasReadStored) {
                $storedEntity = $stored->toEntity();
                $entity = $this->toEntity();
            }

            $isContain = Common::isOneArrayContainOther($entity, $storedEntity);

            if (!$isContain) {
                $updateResult = $this->updateEntity();
            }

            $presentDomain = Common::setIfExists(self::INFORMATION_DOMAIN, $entity, self::EMPTY_VALUE);
            $storedDomain = Common::setIfExists(self::INFORMATION_DOMAIN, $storedEntity, self::EMPTY_VALUE);
            $letSaveDomain = $presentDomain != $storedDomain;

            $saveResult = true;
            if($letSaveDomain){
                $saveResult = $this->saveInformationDomain();
            }

            $result = $saveResult && $updateResult;
            return $result;
        }

        /** Получить параметры поиска по рубрике
         * @return array параметры поиска
         */
        public function getSearchParameter():array
        {

            $result = self::EMPTY_ARRAY;
            return $result;
        }

        /** Установить информационный домен свойства рубрики
         * @param string $code код информационного домена
         * @return bool успех выполнения
         */
        public function setInformationDomain(string $code):bool
        {
            $domain = new InformationDomain();
            $isSuccess = $domain->loadByCode($code);

            if($isSuccess){
                $this->informationDomain=$domain->id;
            }
        }

        /** Загрузить идентификатор информационного домена
         * @return bool успех выполнения
         */
        private function loadInformationDomain():bool
        {
            
            $oneParameter = SqlHandler::setBindParameter(':ID',$this->id,\PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . InformationDomain::EXTERNAL_ID
                . ' FROM '
                . InformationPropertyDomain::TABLE_NAME
                . ' WHERE '
                . self::EXTERNAL_ID
                . ' = '
                . $oneParameter[ISqlHandler::PLACEHOLDER]
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $record = SqlHandler::readOneRecord($arguments);
            
            if ($record != self::EMPTY_ARRAY) {                
                $this->informationDomain = Common::setIfExists(InformationDomain::ID, $record,self::EMPTY_VALUE);
            }

            $result = $this->informationDomain != self::EMPTY_VALUE;
            return $result;
        }

        /** Установить информационный джомен для свойства рубрики
         * @return bool
         */
        private function saveInformationDomain():bool
        {

           $linkToThis = \Assay\DataAccess\Common::setForeignKeyParameter(self::EXTERNAL_ID, $this->id);
            $foreignKeySet[] = $linkToThis;

            $linkage = new InformationPropertyDomain();
            $isSuccess = $linkage->dropInnerLinkage( $foreignKeySet);

            if($isSuccess ){
                $linkToDomain = \Assay\DataAccess\Common::setForeignKeyParameter(InformationDomain::EXTERNAL_ID
                    , $this->informationDomain);
                $foreignKeySet[] = $linkToDomain;

                $isSuccess  = $linkage->addLinkage($foreignKeySet);
            }

            return $isSuccess ;

        }
    }
}
