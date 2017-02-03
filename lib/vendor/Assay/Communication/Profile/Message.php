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
        const MESSAGE_TEXT = 'message_text';
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

        public function isOwn():bool
        {
            if(is_null($this->profileId)) return false;
            return true; //- для тестов пока что предположим, что в собственном
        }

        public function count():int
        {

        }

        /** Получить всех корреспорндентов ( последние сообщения всех переписок )
         * @return array
         */
        public function getCorrespondent():bool
        {
           // if(!$this->isOwnProfile()) return false;

            $oneParameter[ISqlHandler::PLACEHOLDER] = ':RECEIVER';
            $oneParameter[ISqlHandler::VALUE] = $this->profileId;
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;



            $arguments[ISqlHandler::QUERY_TEXT] = 'SELECT DISTINCT ON (M.'.self::AUTHOR.') M.'.self::AUTHOR.', M.'.self::DATE.', M.'.self::MESSAGE_TEXT
                .', (SELECT name FROM '.PersonProfile::TABLE_NAME.' WHERE '.PersonProfile::ID.' = M.'.self::AUTHOR.') as author_name'
                .'  FROM '.self::TABLE_NAME.' as M '
                . ' WHERE 
                 M.'.self::PROFILE.' = '.$oneParameter[ISqlHandler::PLACEHOLDER]
                .' ORDER BY  M.'.self::AUTHOR.', M.'.self::DATE.' DESC;';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter; //print_r($arguments);

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); print_r($response);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = [];
            $result = false;
            if ($isSuccessfulRead) {
                $record = SqlHandler::getAllRecords($response);
                if(count($record) > 0) {
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
        public function getByCorrespondent($authorId):bool
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
                . ' WHERE '.self::RECEIVER . ' = ' . $twoParameter[ISqlHandler::PLACEHOLDER]
                . ' AND '.self::AUTHOR . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER];

            $arguments[ISqlHandler::QUERY_PARAMETER] = [$oneParameter, $twoParameter];

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); //print_r($response);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response); //print_r($record);
                if(count($record) == 0){
                    return false;
                }
            }

            $oneParameter[ISqlHandler::PLACEHOLDER] = ':RECEIVER';
            $oneParameter[ISqlHandler::VALUE] = $this->profileId;
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $twoParameter[ISqlHandler::PLACEHOLDER] = ':AUTHOR';
            $twoParameter[ISqlHandler::VALUE] = $this->authorId;
            $twoParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] = 'SELECT M.'.self::DATE.', M.'.self::MESSAGE_TEXT
                .', (SELECT name FROM '.PersonProfile::TABLE_NAME.' WHERE '.PersonProfile::ID.' = M.'.self::AUTHOR.') as Author_name'
                .', (SELECT name FROM '.PersonProfile::TABLE_NAME.' WHERE '.PersonProfile::ID.' = M.'.self::RECEIVER.') as Receiver_name'
                .'  FROM '.self::TABLE_NAME.' as M '
                . ' WHERE ('
                . ' M.'.self::RECEIVER.' = '.$oneParameter[ISqlHandler::PLACEHOLDER]
                . ' AND M.'.self::AUTHOR.' = '.$twoParameter[ISqlHandler::PLACEHOLDER]
                .')'
                . ' OR
                ('
                . ' M.'.self::RECEIVER.' = '.$twoParameter[ISqlHandler::PLACEHOLDER]
                . ' AND M.'.self::AUTHOR.' = '.$oneParameter[ISqlHandler::PLACEHOLDER]
                .')
                 ORDER BY M.'.self::DATE.' DESC;';

            // print_r($arguments[ISqlHandler::QUERY_TEXT]);
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$oneParameter, $twoParameter]; //print_r($arguments[ISqlHandler::QUERY_PARAMETER]);

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);// print_r($sqlReader);
            $response = $sqlReader->performQuery($arguments); //print_r($response);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = [];
            $result = false;
            if ($isSuccessfulRead) {
                $record = SqlHandler::getAllRecords($response); //print_r($record);
                if(count($record) > 0) {
                    $result = true;
                    $this->messagesSelectAuthor = $record;
                }
            }
            return $result;

        }




        public function saveGoodsOrder():bool
        {
        }

        public function saveShippingOrder():bool
        {
        }
    }
}