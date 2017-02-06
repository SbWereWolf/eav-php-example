<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:00
 */
namespace Assay\Core {

    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    /**
     * реализация интерфейса для работы с именнуемыми сущностями
     */
    class Entity extends PrimitiveData implements IEntity
    {
        /** @var null константа значение не задано для ссылочных типов */
        const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
        /** @var array константа значение не задано для массивов */
        const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'entity';
        
        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;

        /** @var string флаг "является скрытым" */
        public $isHidden = self::EMPTY_VALUE ;

        public function addEntity():bool
        {
            $arguments[ISqlHandler::QUERY_TEXT] =
                ' INSERT INTO ' . $this->tablename
                . ' DEFAULT VALUES RETURNING '
                . self::ID
                .' , '.self::IS_HIDDEN 
                .' ; '
                ;

            $record = SqlHandler::readOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }

            $result = $result && $this->id != self::EMPTY_VALUE &&  $this->isHidden  != self::EMPTY_VALUE;

            return $result;
        }

    }
}
