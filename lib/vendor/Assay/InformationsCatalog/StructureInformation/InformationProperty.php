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

    /**
     * Свойство рубрики
     */
    class InformationProperty extends NamedEntity implements IInformationProperty
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'information_property_id';

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'information_property';

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
            $this->loadInformationDomain();

            $result = $isSuccessfulByCode;

            return $result;
        }

        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return bool успех выполнения
         */
        public function loadById(string $id):bool
        {
            $isSuccessfulById = parent::loadById($id);
            $this->loadInformationDomain();

            $result = $isSuccessfulById;

            return $result;
        }

        /** Установить свойства в соответствии со значениями элементов массива
         * @param array $namedValue массив с именованными элементами
         * @return bool успех выполнения
         */
        public function setByNamedValue(array $namedValue):bool
        {

            parent::setByNamedValue($namedValue);

            $informationDomain = Common::setIfExists(self::INFORMATION_DOMAIN, $namedValue, self::EMPTY_VALUE);
            if ($informationDomain != self::EMPTY_VALUE) {
                $this->informationDomain = $informationDomain;
            }

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
            if ($letSaveDomain) {
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

            if ($isSuccess) {
                $this->informationDomain = $domain->id;
            }

            return $isSuccess;
        }

        /** Загрузить идентификатор информационного домена
         * @return bool успех выполнения
         */
        private function loadInformationDomain():bool
        {

            $linkage = new DomainInformationProperty();

            $isSuccess = $linkage->loadByRight($this->id);
            if ($isSuccess) {
                $this->informationDomain = $linkage->leftId;
            }

            $result = $isSuccess && $this->informationDomain != self::EMPTY_VALUE;
            return $result;
        }

        /** Установить информационный джомен для свойства рубрики
         * @return bool
         */
        private function saveInformationDomain():bool
        {

            $linkage = new DomainInformationProperty();
            $linkage->dropLinkageByRight($this->id);

            $isSuccess = $linkage->addInnerLinkage($this->informationDomain, $this->id);

            return $isSuccess;

        }
    }
}
