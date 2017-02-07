<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:41
 */
namespace Assay\Communication\Profile {

    //class Message extends SocialElement implements IMessage

    use \Assay\DataAccess\ISqlHandler;
    use Assay\Core\NamedEntity;
    use \Assay\DataAccess\SqlHandler;
    use Assay\Communication\Profile\Common;
    use Assay\Core\PredefinedEntity;

    class Message extends PredefinedEntity implements IMessage
    {
        const GOODS_ORDER_PATTERN = 'GOODS_ORDER_PATTERN';
        const SHIPPING_ORDER_PATTERN = 'SHIPPING_ORDER_PATTERN';

        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'message_id';

        const PROFILE = 'receiver';
        const AUTHOR = 'author';

        /** @var string имя таблицы */
        const TABLE_NAME = 'message';

        /** @var string разделитель слов в названиях */
        const WORD_DIVIDER = '_';

        /** @var string колонка идентификатора */
        const ID = 'id';
        // const MESSAGE_TEXT = 'message_text';
        //   const CONTENT = 'message_text';
        const DATE = 'date';
        const DEFAULT_IS_HIDDEN = 0;

        public $correspondent;

        /** @var string идентификатор записи таблицы */
        public $id = self::EMPTY_VALUE;
        /** @var string наименование */
        public $author = self::EMPTY_VALUE;
        /** @var string описание */
        public $profile = self::EMPTY_VALUE;
        public $messageText = self::EMPTY_VALUE;
        public $date = self::EMPTY_VALUE;

        protected $tablename = self::TABLE_NAME;

        public $messagesSelectAuthor = [];
        public $messageList = [];
        public $profileId = NULL;
        public $authorId = NULL;
        public $isHidden = self::DEFAULT_IS_HIDDEN;

        public $fieldTypes = [
            'id' => 'PARAM_INT',
            'author' => 'PARAM_INT',
            'receiver' => 'PARAM_INT',
            'message_text' => 'PARAM_STR',
            'date' => 'PARAM_STR',
            'is_hidden' => 'PARAM_INT'
        ];


        public function __construct($profileId)
        {
            $this->profileId = $profileId;
            //$this->getMode();
        }

        public function isOwn(): bool
        {
            if (is_null($this->profileId)) return false;
            return true; //- для тестов пока что предположим, что в собственном
        }

        public function count(): int
        {

        }

        /** Получить всех корреспорндентов ( последние сообщения всех переписок )
         * @return array
         */
        public function getCorrespondent(): bool
        {
            // if(!$this->isOwnProfile()) return false;

            $oneParameter[ISqlHandler::PLACEHOLDER] = ':RECEIVER';
            $oneParameter[ISqlHandler::VALUE] = $this->profileId;
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;


            $arguments[ISqlHandler::QUERY_TEXT] = 'SELECT DISTINCT ON (M.' . self::AUTHOR . ') M.' . self::AUTHOR . ', M.' . self::DATE . ', M.' . self::MESSAGE_TEXT
                . ', (SELECT name FROM ' . PersonProfile::TABLE_NAME . ' WHERE ' . PersonProfile::ID . ' = M.' . self::AUTHOR . ') as author_name'
                . '  FROM ' . self::TABLE_NAME . ' as M '
                . ' WHERE 
                 M.' . self::PROFILE . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER]
                . ' ORDER BY  M.' . self::AUTHOR . ', M.' . self::DATE . ' DESC;';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter; //print_r($arguments);

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); //print_r($response);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = [];
            $result = false;
            if ($isSuccessfulRead) {
                $record = SqlHandler::getAllRecords($response);
                if (count($record) > 0) {
                    $result = true;
                    $this->messageList = $record;

                    //    if($record["author_is_company"] == 1) $this->messageList["author_name"] = $record["author_name_company"];
                    //   else $this->messageList["author_name"] = $record["author_name_user"];


                }
            }
            return $result;
        }

        /** получить переписку с корреспондентом
         * @return array
         */
        public function getByCorrespondent($authorId): bool
        {
            $messages = [];
            $this->authorId = $authorId;
            //    if(!$this->isOwnProfile()) return false;

            //проверяем, есть ли у нас вообще сообщения от этого автора, если нет, то шлем лесом

            $oneParameter[ISqlHandler::PLACEHOLDER] = ':AUTHOR';
            $oneParameter[ISqlHandler::VALUE] = $this->authorId;
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $twoParameter[ISqlHandler::PLACEHOLDER] = ':RECEIVER';
            $twoParameter[ISqlHandler::VALUE] = $this->profileId;
            $twoParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT count(*) FROM message'
                . ' WHERE ' . self::PROFILE . ' = ' . $twoParameter[ISqlHandler::PLACEHOLDER]
                . ' AND ' . self::AUTHOR . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER];

            $arguments[ISqlHandler::QUERY_PARAMETER] = [$oneParameter, $twoParameter];

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); //print_r($response);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response); //print_r($record);
                if (count($record) == 0) {
                    return false;
                }
            }

            $oneParameter[ISqlHandler::PLACEHOLDER] = ':RECEIVER';
            $oneParameter[ISqlHandler::VALUE] = $this->profileId;
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $twoParameter[ISqlHandler::PLACEHOLDER] = ':AUTHOR';
            $twoParameter[ISqlHandler::VALUE] = $this->authorId;
            $twoParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] = 'SELECT M.' . self::DATE . ', M.' . self::CONTENT
                . ', (SELECT name FROM ' . PersonProfile::TABLE_NAME . ' WHERE ' . PersonProfile::ID . ' = M.' . self::AUTHOR . ') as Author_name'
                . ', (SELECT name FROM ' . PersonProfile::TABLE_NAME . ' WHERE ' . PersonProfile::ID . ' = M.' . self::PROFILE . ') as Receiver_name'
                . '  FROM ' . self::TABLE_NAME . ' as M '
                . ' WHERE ('
                . ' M.' . self::PROFILE . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER]
                . ' AND M.' . self::AUTHOR . ' = ' . $twoParameter[ISqlHandler::PLACEHOLDER]
                . ')'
                . ' OR
                ('
                . ' M.' . self::PROFILE . ' = ' . $twoParameter[ISqlHandler::PLACEHOLDER]
                . ' AND M.' . self::AUTHOR . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER]
                . ')
                 ORDER BY M.' . self::DATE . ' DESC;';

            // print_r($arguments[ISqlHandler::QUERY_TEXT]);
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$oneParameter, $twoParameter]; //print_r($arguments[ISqlHandler::QUERY_PARAMETER]);

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);// print_r($sqlReader);
            $response = $sqlReader->performQuery($arguments); //print_r($response);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = [];
            $result = false;
            if ($isSuccessfulRead) {
                $record = SqlHandler::getAllRecords($response); //print_r($record);
                if (count($record) > 0) {
                    $result = true;
                    $this->messagesSelectAuthor = $record;
                }
            }
            return $result;

        }

        //создаем обычное сообщение
        public function addMessage(array $values): bool
        {

            $result = false;
            //   if(!$this->isOwnProfile()) return false;
            $params = Common::getFieldsList(__CLASS__);
            unset($params[self::GOODS_ORDER_PATTERN]);
            unset($params[self::SHIPPING_ORDER_PATTERN]);
            unset($params[self::PARENT_TABLE_NAME]);
            unset($params[self::CHILD]);
            //  unset($params[self::CHILD]);
            //    print_r($params);

            foreach ($params as $key => $value) {
                //print_r($key);
                $keyCamel = Common::camelCase($key, [], self::WORD_DIVIDER);
                $k = __CLASS__ . '::' . $value;
                $v = constant($k);
                if (isset($values[$key]))
                    $this->{$keyCamel} = $values[$key];
                else {
                    if ($this->fieldTypes[$key] == 'PARAM_INT') $this->{$keyCamel} = 0;//self::EMPTY_VALUE;
                    else $this->{$keyCamel} = self::EMPTY_VALUE;

                }                // print_r($key);
            }
            $this->addEntity();
            $this->date = date('Y-m-d H:i:s');
            $result = $this->mutateEntity();

            //  if($this->mutateEntity() && $this->loadById($this->id))
            //      $result = true;
            //           print_r($result);
            return $result;

            // if($this->loadById($profileId)) $result = true;
            // return $this->loadById($profileId);
        }


        /** Добавляет запись в БД
         * @return bool успешность изменения
         */

        public function addEntity(): bool
        {
            $arguments[ISqlHandler::QUERY_TEXT] =
                ' INSERT INTO ' . $this->tablename
                . ' DEFAULT VALUES RETURNING '
                . self::ID
                . ' ; ';
            $sqlWriter = new SqlHandler(ISqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);
            $record = self::EMPTY_ARRAY;
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
            }

            $this->id = \Assay\Core\Common::setIfExists(self::ID, $record, self::EMPTY_VALUE);
//            $this->isHidden = \Assay\Core\Common::setIfExists(self::IS_HIDDEN, $record, self::EMPTY_VALUE);

            $result = $this->id != self::EMPTY_VALUE;

            return $result;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity(): bool
        {
            $result = false;
            $stored = new Messages($this->profileId);
            // $this->id = 1;
            $wasReadStored = $stored->loadById($this->id);

            $storedEntity = array();
            $entity = array();
            if ($wasReadStored) {
                $storedEntity = $stored->toEntity(); //print_r($storedEntity);
                $entity = $this->toEntity(); //print_r($entity);
            }

            $isContain = \Assay\Core\Common::isOneArrayContainOther($entity, $storedEntity);
            // if (!$isContain) {
            $result = $this->updateEntity();
            // }
            //print_r($result);
            return $result;
        }

        public function toEntity(): array
        {
            $result = [];
            $params = Common::getFieldsList(__CLASS__);
            unset($params[self::GOODS_ORDER_PATTERN]);
            unset($params[self::SHIPPING_ORDER_PATTERN]);
            unset($params[self::PARENT_TABLE_NAME]);
            unset($params[self::CHILD]);

            foreach ($params as $key => $value) {

                $keyCamel = Common::camelCase($key, [], self::WORD_DIVIDER);
                $k = __CLASS__ . '::' . $value;
                $v = constant($k);
                // $this->{$key} = Common::setIfExists($v, $namedValue, self::EMPTY_VALUE);
                $result[$v] = $this->{$keyCamel};

            }

            return $result;
        }

        protected function updateEntity(): bool
        {

            $sqlText = '';
            $params = Common::getFieldsList(__CLASS__);
            unset($params[self::GOODS_ORDER_PATTERN]);
            unset($params[self::SHIPPING_ORDER_PATTERN]);
            unset($params[self::PARENT_TABLE_NAME]);
            unset($params[self::CHILD]);
            //    $end_element = array_pop($params); //потому что после последнего элемента нам не надо ставить запятую

            foreach ($params as $key => $value) {
                $propertyName = Common::camelCase($key, [], self::WORD_DIVIDER);

                $$key[ISqlHandler::PLACEHOLDER] = ':' . $value;
                $$key[ISqlHandler::VALUE] = $this->{$propertyName};
                $$key[ISqlHandler::DATA_TYPE] = constant('\PDO::' . $this->fieldTypes[$key]);//$this->{$propertyName};

                $sqlText .= constant('self::' . $value) . " = " . $$key[ISqlHandler::PLACEHOLDER] . ", ";

                $arguments[ISqlHandler::QUERY_PARAMETER][] = $$key;
            }
            //убираем запятую после последнего элемента
            $sqlText = mb_substr(rtrim($sqlText), 0, -1) . ' ';

            $arguments[ISqlHandler::QUERY_TEXT] = '
               UPDATE 
                    ' . $this->tablename . '
                SET 
                    ' . $sqlText . '
                 WHERE
                    ' . self::ID . ' = ' . $id[ISqlHandler::PLACEHOLDER];

//            print_r($arguments);
            //   return;


            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);
            print_r($response);

            $isSuccessfulRequest = SqlHandler::isNoError($response);
            return $isSuccessfulRequest;
        }


        public function saveGoodsOrder(): bool
        {
        }

        public function saveShippingOrder(): bool
        {
        }
    }
}