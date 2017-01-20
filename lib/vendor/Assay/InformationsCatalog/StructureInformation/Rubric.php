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

    /**
     * Рубрика каталога
     */
    class Rubric extends NamedEntity implements IRubric
    {
        const TABLE_NAME = 'rubric';

        protected $tablename = self::TABLE_NAME;

        /** Получить описания позиций рубрики
         * @return array позиции
         */
        public function getMap():array
        {
        }

        /** Получить параметры поиска по рубрике
         * @return array параметры поиска
         */
        public function getSearchParameters():array
        {
        }

        /** Получить свойства рубрики
         * @return array свойства рубрики
         */
        public function getProperties():array
        {
        }
        /** Загрузить по коду записи
         * @param string $code код записи
         * @return bool успех выполнения
         */
        /*        
                public function loadByCode(string $code):bool
                {
                    $result = false;
                    return $result;
                }
                
        */
        /** Загрузить данные в соответствии с идентификатором
         * @param string $id идентификатор записи
         * @return bool успех выполнения
         */
        /*
                public function loadById(string $id):bool{
                    $result = false;
                    return $result;
                }
        */

        /** Загрузить данные сохранённые в БД
         * @return bool успех выполнения
         */
        /*
                public function getStored():bool{
                    $result = false;
                    return $result;
                }
        
                */
        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        /*
                public function toEntity():array{
                    $result =array();
                    return $result;
                }
                */
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

        /** Обновить данные в БД
         * @return bool успех выполнения
         */
        private function updateEntity():bool
        {

            $codeParameter[ISqlHandler::PLACEHOLDER] = ':CODE';
            $codeParameter[ISqlHandler::VALUE] = $this->code;
            $codeParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $descriptionParameter[ISqlHandler::PLACEHOLDER] = ':DESCRIPTION';
            $descriptionParameter[ISqlHandler::VALUE] = $this->description;
            $descriptionParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $idParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $idParameter[ISqlHandler::VALUE] = intval($this->id);
            $idParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $isHiddenParameter[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $isHiddenParameter[ISqlHandler::VALUE] = intval($this->isHidden);
            $isHiddenParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $nameParameter[ISqlHandler::PLACEHOLDER] = ':NAME';
            $nameParameter[ISqlHandler::VALUE] = $this->name;
            $nameParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'UPDATE '
                . self::TABLE_NAME
                . ' SET '
                . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::NAME . ' = ' . $nameParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::DESCRIPTION . ' = ' . $descriptionParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $nameParameter;

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRequest = SqlHandler::isNoError($response);
            return $isSuccessfulRequest;
        }

        /** Установить свойства экземпляра в соответствии со значениями
         * @param array $namedValue массив значений
         * @return bool успех выполнения
         */
        /*
                public function setByNamedValue(array $namedValue):bool{
                    $result = false;
                    return $result;
                }
                */
    }
}
