<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:09
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core;
    use Assay\Core\ICommon;
    use Assay\DataAccess\DbCredentials;
    use Assay\DataAccess\SqlHandler;

    /**
     * Древовидная структура
     */
    class Structure implements IStructure,
        \Assay\Core\IHide,
        \Assay\Core\INamedEntity,
        Core\IMutableEntity,
        Core\IReadableEntity
    {
        /** @var string колонка идентификатора */
        const ID = 'id';

        /** @var string идентификатор записи таблицы */
        public $id;
        /** @var string признак "является скрытым" */
        public $isHidden;
        /** @var string дата добавления записи */
        public $insertDate;
        /** @var string родительский элемент */
        public $parent;
        /** @var string код записи */
        public $code;
        /** @var string наименование */
        public $name;
        /** @var string описание */
        public $description;



        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return bool значения колонок
         */
        public function readEntity(string $id):bool
        {
            $oneParameter[SqlHandler::QUERY_PLACEHOLDER] = ':ID';
            $oneParameter[SqlHandler::QUERY_VALUE] = strval($id);
            $oneParameter[SqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[SqlHandler::QUERY_TEXT] =
                'SELECT '
                . Structure::ID
                . ' , '
                . Structure::PARENT
                . ' , '
                . Structure::CODE
                . ' , '
                . Structure::NAME
                . ' , '
                . Structure::DESCRIPTION
                . ' , '
                . Structure::IS_HIDDEN
                . ' , '
                . Structure::INSERT_DATE
                .' FROM '
                . IStructure::TABLE_NAME
                . ' WHERE '
                . Structure::ID
                . ' = '
                . $oneParameter[SqlHandler::QUERY_PLACEHOLDER]
                . '
;
';
            $arguments[SqlHandler::QUERY_PARAMETER][] = $oneParameter;
            
            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            
            $response = $sqlReader->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = array();
            if($isSuccessfulRead){
                $record = SqlHandler::getFirstRecord($response);
            }

            $this->setByNamedValue($record);

            return $isSuccessfulRead;
        }

        /** Прочитать данные экземпляра из БД
         * @return bool колонки
         */
        public function getStored():bool
        {
            $result= $this->readEntity($this->id);
            return $result;
        }

        /** Установить свойства экземпляра в соответствии с массивом
         * @param array $namedValue массив значений
         */
        public function setByNamedValue(array $namedValue)
        {
            $this->code = Core\Common::setIfExists(self::CODE, $namedValue, Core\Common::EMPTY_VALUE);
            $this->description = Core\Common::setIfExists(self::DESCRIPTION, $namedValue, Core\Common::EMPTY_VALUE);
            $this->id = Core\Common::setIfExists(self::ID, $namedValue, Core\Common::EMPTY_VALUE);
            $this->insertDate = Core\Common::setIfExists(self::INSERT_DATE, $namedValue, Core\Common::EMPTY_VALUE);
            $this->isHidden = Core\Common::setIfExists(self::IS_HIDDEN, $namedValue, Core\Common::EMPTY_VALUE);
            $this->name = Core\Common::setIfExists(self::NAME, $namedValue, Core\Common::EMPTY_VALUE);
            $this->parent = Core\Common::setIfExists(self::PARENT, $namedValue, Core\Common::EMPTY_VALUE);
        }

        /** Добавить запись в БД на основе экземпляра
         * @param array $namedValue значения колонок
         * @return bool успех выполнения
         */
        public function addReadable(array $namedValue):bool
        {
            $linkToParent = Core\Common::setIfExists(
                IStructure::TABLE_NAME,
                $namedValue,
                Core\Common::EMPTY_VALUE);
            $parent = Core\Common::EMPTY_VALUE;
            if ($linkToParent != Core\Common::EMPTY_VALUE) {
                $parent = Core\Common::setIfExists(
                    IStructure::PARENT,
                    $linkToParent,
                    Core\Common::EMPTY_VALUE);
            }
            if ($parent != Core\Common::EMPTY_VALUE) {
                $parent = intval($parent);
            }
            if ($parent == 0) {
                $parent = null;
            }

            $oneParameter[SqlHandler::QUERY_PLACEHOLDER] = ':PARENT';
            $oneParameter[SqlHandler::QUERY_VALUE] = $parent;
            $oneParameter[SqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[SqlHandler::QUERY_TEXT] =
                'INSERT INTO '
                . IStructure::TABLE_NAME
                . ' (' . IStructure::PARENT . ')'
                . ' VALUES('
                . $oneParameter[SqlHandler::QUERY_PLACEHOLDER]
                . ')
                RETURNING ' . Structure::ID
                . ' , '
                . Structure::IS_HIDDEN
                . ' , '
                . Structure::INSERT_DATE
                . ' , '
                . Structure::PARENT
                . ' , '
                . Structure::CODE
                . ' , '
                . Structure::NAME
                . ' , '
                . Structure::DESCRIPTION

                . '
;
';
            $arguments[SqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRequest = SqlHandler::isNoError($response);
            $record = array();
            if($isSuccessfulRequest){
                $record = SqlHandler::getFirstRecord($response);
            }

            $this->setByNamedValue($record);

            return $isSuccessfulRequest;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool
        {
        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {
        }

        /** Чтение записи из БД по коду
         * @param string $code код записи
         * @return array значения записи
         */
        public function loadByCode(string $code):array
        {
        }

        /** Получить имя и описание записи
         * @return array массив с именем и описанием
         */
        public function getElementDescription():array
        {
        }

        /** Скрыть сущность
         * @return bool успех операции
         */
        public function hideEntity():bool
        {
            $arguments[SqlHandler::QUERY_TEXT] =
                'UPDATE '
                . IStructure::TABLE_NAME
                . ' SET ' . Core\IHide::IS_HIDDEN . ' ='
                . Core\IHide::DEFINE_AS_HIDDEN
                . ' WHERE '
                . self::ID . ' = '
                . $this->id
                . ';';
            
            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);
            $result = $response[SqlHandler::ERROR_INFO][SqlHandler::EXEC_ERROR_CODE_INDEX] == SqlHandler::EXEC_WITH_SUCCESS;

            if ($result) {
                $this->isHidden = true;
            }
            return $result;
        }

        /** Добавить дочерний элемент
         * @return string идентификатор добавленого элемента
         */
        public function addChild():string
        {

        }

        /** Получить имена дочерних элементов
         * @return array имена элементов
         */
        public function getChildrenNames():array
        {

        }

        /** Получить получить идентификатор ролительского элемнта
         * @return string идентификатор
         */
        public function getParent():string
        {

        }

        /** Проверить что является разделом
         * @return bool успех проверки
         */
        public function isPartition():bool
        {

        }

        /** Проверить что является рубрикой
         * @return bool успех проверки
         */
        public function isRubric():bool
        {

        }

        /** Получить путь от этого элемента до корневого
         * @return array элменты пути
         */
        public function getPath():array
        {

        }

        /** Получить описание всех элементов
         * @return array элменты пути
         */
        public function getMap():array
        {

        }

        /** Выполнить поиск
         * @param string $searchString поисковая строка
         * @param string $structureCode код корневого элемента
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public function search(string $searchString = ICommon::EMPTY_VALUE, string $structureCode = ICommon::EMPTY_VALUE, int $start, int $paging):array
        {

        }
    }
}
