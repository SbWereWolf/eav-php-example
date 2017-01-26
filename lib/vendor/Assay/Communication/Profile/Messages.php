<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:42
 */
namespace Assay\Communication\Profile {

    use Assay\Core;
    use \Assay\Core\Common;
    use \Assay\DataAccess\ISqlHandler;
    use \Assay\DataAccess\SqlHandler;
    use Assay\Permission\Privilege;

    class Messages extends \Assay\Core\Entity //implements ICompany
    {
        /** @var string константа для не пустого значения */
        const EMPTY_VALUE = '';

        /** @var string имя таблицы */
        const TABLE_NAME = 'messages';

        /** @var string разделитель слов в названиях */
        const WORD_DIVIDER = '_';

        /** @var string колонка идентификатора */
        const ID = 'id';
        const AUTHOR = 'author';
        const RECEIVER = 'receiver';
        const MESSAGE_TEXT = 'message_text';
        const DATE = 'date';

        /** @var string идентификатор записи таблицы */
        public $id = self::EMPTY_VALUE;
        /** @var string наименование */
        public $author = self::EMPTY_VALUE;
        /** @var string описание */
        public $receiver = self::EMPTY_VALUE;
        public $messageText = self::EMPTY_VALUE;
        public $date = self::EMPTY_VALUE;

       protected $tablename = self::TABLE_NAME;

        public $messages = [];
        public $profileId = NULL;

        public $fieldTypes = [
            'id' => 'PARAM_INT',
            'author' => 'PARAM_INT',
            'receiver' => 'PARAM_INT',
            'message_text' => 'PARAM_STR',
            'date' => 'PARAM_STR'
             ];

       // public $profile; //- это id


        //получаем список полей таблицы
        public function getFieldsList():array
        {
            $refl = new \ReflectionClass(__CLASS__);
            $params = $refl->getConstants();
            $strangeParams = [];
            foreach ($params as $key => $value)
            {
                if(is_array($value)) continue;
                $stringKey = (string) $key;
                $stringValue = (string) $value;
                $strangeParams[$stringValue] = $stringKey;
            }

            unset($strangeParams[self::TABLE_NAME]);
            unset($strangeParams[self::EMPTY_VALUE]);
            unset($strangeParams[self::EMPTY_OBJECT]);
            unset($strangeParams[self::DEFINE_AS_HIDDEN]);
            unset($strangeParams[self::DEFINE_AS_NOT_HIDDEN]);
            unset($strangeParams[self::DEFAULT_IS_HIDDEN]);
            unset($strangeParams[self::WORD_DIVIDER]);
            unset($strangeParams[self::IS_HIDDEN]);
            print_r($strangeParams);

            return $strangeParams;
        }

        //camelCase
        public static function camelCase($propertyName, array $noStrip = [])
        {
            // non-alpha and non-numeric characters become spaces
          //  $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
            $propertyName = trim($propertyName);
            // uppercase the first character of each word
            $propertyName = ucwords($propertyName, self::WORD_DIVIDER);
            $propertyName = str_replace(" ", "", $propertyName);
            $propertyName = str_replace(self::WORD_DIVIDER, "", $propertyName);
            $propertyName = lcfirst($propertyName);

            return $propertyName;
        }


        /**
         * узнаем, в собственном профиле мы находимся или в чужом
         * @return bool
         */
        public function isOwnProfile()
        {
            if(is_null($this->profileId)) return false;
            return true; //- для тестов пока что предположим, что в собственном
        }

        //получаем список сообщений
        public function getMessagesList():bool
        {
            $messages = [];
            if(!$this->isOwnProfile()) return false;

            $oneParameter[ISqlHandler::PLACEHOLDER] = ':RECEIVER';
            $oneParameter[ISqlHandler::VALUE] = $this->profileId;
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] = 'SELECT DISTINCT ON (M.'.self::AUTHOR.') M.'.self::AUTHOR.', M.'.self::DATE.', M.'.self::MESSAGE_TEXT
                .', (SELECT name FROM '.Profile::TABLE_NAME.' WHERE '.Profile::ID.' = M.'.self::AUTHOR.') as Author_name'
                .'  FROM '.self::TABLE_NAME.' as M '
                . ' WHERE 
                 M.'.self::RECEIVER.' = '.$oneParameter[ISqlHandler::PLACEHOLDER]
                .' ORDER BY  M.'.self::AUTHOR.', M.'.self::DATE.' DESC;';

                /*'
                SELECT DISTINCT ON (M.'.self::AUTHOR.'), M.'.self::DATE.', M.'.self::MESSAGE_TEXT
                .', (SELECT name FROM '.Profile::TABLE_NAME.' WHERE '.Profile::ID.' = M.'.self::AUTHOR.') as Author_name'
                .'  FROM '.self::TABLE_NAME.' as M, '.Profile::TABLE_NAME.' as P '
                . ' WHERE 
                P.'.Profile::ID.' = '.$oneParameter[ISqlHandler::PLACEHOLDER].' AND 
                M.'.self::RECEIVER.' = '.$oneParameter[ISqlHandler::PLACEHOLDER]
                .' ORDER BY M.'.self::DATE.' DESC;';
            */


            /*
             * 
             *                 WHERE 
                    S.'.self::IS_HIDDEN.'='.$is_hidden[ISqlHandler::PLACEHOLDER].' AND 
                    S.'.self::ID.'='.$id_field[ISqlHandler::PLACEHOLDER].' AND 
                    S.'.self::USER_ID.'=A.'.self::ID.' AND 
                    A.'.self::ID.'=AR.'.Account::EXTERNAL_ID.' AND 
                    R.'.self::ID.'=AR.'.BusinessRole::EXTERNAL_ID.'
             * 
             */

    //        print_r($arguments[ISqlHandler::QUERY_TEXT]);
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); print_r($response);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = [];
            if ($isSuccessfulRead) {
                $record = SqlHandler::getAllRecords($response);
            }
           // print_r($record);

            if ($isSuccessfulRead) {
                $record = SqlHandler::getAllRecords($response);
                if(count($record) > 0) $result = true;
                //$this->setByNamedValue($record);
            }

            return $result;
        }

        public function loadById(string $id):bool
        {
            $oneParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::VALUE] = intval($id);
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $params = $this->getFieldsList();

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . implode(',', array_keys($params))
                . ' FROM '
                . $this->tablename
                . ' WHERE '
                . self::ID. ' = '. $oneParameter[ISqlHandler::PLACEHOLDER]
                . '
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); //print_r($response);

            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            $result = false;
            if ($record != array()) {
                $result = true;
            }

            return $result;
        }

        public function setByNamedValue(array $namedValue):bool
        {
            /*
            $this->code = Common::setIfExists(self::CODE, $namedValue, self::EMPTY_VALUE);
            $this->description = Common::setIfExists(self::DESCRIPTION, $namedValue, self::EMPTY_VALUE);
            $this->id = Common::setIfExists(self::ID, $namedValue, self::EMPTY_VALUE);
            $this->isHidden = Common::setIfExists(self::IS_HIDDEN, $namedValue, self::EMPTY_VALUE);
            $this->name = Common::setIfExists(self::NAME, $namedValue, self::EMPTY_VALUE);

            parent::setByNamedValue($namedValue);
            $this->city = Common::setIfExists(self::CITY, $namedValue, self::EMPTY_VALUE);
            $this->country = Common::setIfExists(self::COUNTRY, $namedValue, self::EMPTY_VALUE);
            */

            $params = $this->getFieldsList();

            foreach($params as $key => $value)
            {
                /*
                $value = self::camelCase($value);
                $k = 'Assay\Communication\Profile\Company::'.$key;
                $v = constant($k);
                $this->{$value} = Common::setIfExists($v, $namedValue, self::EMPTY_VALUE);
                */
                $keyCamel = self::camelCase($key);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
                $this->{$keyCamel} = Common::setIfExists($v, $namedValue, self::EMPTY_VALUE);

            }

            return true;
        }

        //создаем профиль компании
        public function addCompanyData(array $values):bool
        {
            //$session = new Assay\Permission\Privilege\Session();

            //  $profileId = $session->profile;
            //$profileId = 1; //для тестов
            //$profileId = $this->id;
            $result = false;

            $result = false;


            $params = $this->getFieldsList();

            foreach($params as $key => $value)
            {
                //print_r($key);
                $key = self::camelCase($key);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
                if(isset($values[$key]))
                    $this->{$key} = $values[$key];
                else $this->{$key} = self::EMPTY_VALUE;//Common::setIfExists($v, $values[$key], self::EMPTY_VALUE);
                // print_r($key);
            }

            $this->addEntity();
            $result = $this->mutateEntity();

            //  if($this->mutateEntity() && $this->loadById($this->id))
            //      $result = true;
            //           print_r($result);
            return $result;

            // if($this->loadById($profileId)) $result = true;
            // return $this->loadById($profileId);
        }

        //обновляем профиль компании
        public function setCurrentUserCompanyData(array $values):bool
        {
            //$session = new Assay\Permission\Privilege\Session();

            //  $profileId = $session->profile;
            //$profileId = 1; //для тестов
            //$profileId = $this->id;
            $result = false;


            $params = $this->getFieldsList();

            foreach($params as $key => $value)
            {
                //print_r($key);
                $keyCamel = self::camelCase($key);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
                if(isset($values[$keyCamel]) && !empty($values[$keyCamel]))
                    $this->{$keyCamel} = $values[$keyCamel];
                else {
                    if($this->fieldTypes[$key] == 'PARAM_INT')  $this->{$keyCamel} = self::EMPTY_VALUE;
                    else $this->{$keyCamel} = self::EMPTY_VALUE;

                }//self::EMPTY_VALUE;//Common::setIfExists($v, $values[$key], self::EMPTY_VALUE);
               // print_r($key);
            }
//print_r($this); $a = NULL; echo("sdfdsfsdf".$a);
            /*
            $this->update_date = time();
            $this->name = $name;
            $this->isHidden = $isHidden;
            $this->code = $code;
            $this->description = $description;
            $this->city = $city;
            $this->country = $country;
            */

           // $this->addEntity();
            //$result = $this->mutateEntity();

          //  print_r($this); return true;

            if($this->mutateEntity() && $this->loadById($this->id))
                $result = true;
 //           print_r($result);
            return $result;

            // if($this->loadById($profileId)) $result = true;




           // return $this->loadById($profileId);
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool
        {
            $result = false;

            $stored = new Company();
           // $this->id = 1;
            $wasReadStored = $stored->loadById($this->id);

            $storedEntity = array();
            $entity = array();
            if ($wasReadStored) {
                $storedEntity = $stored->toEntity(); //print_r($storedEntity);
                $entity = $this->toEntity(); //print_r($entity);
            }

            $isContain = Common::isOneArrayContainOther($entity, $storedEntity);
           // if (!$isContain) {
                $result = $this->updateEntity();
           // }
//print_r($result);
            return $result;
        }

        public function toEntity():array
        {
            $result = [];
/*
            $result [self::CODE] = $this->code;
            $result [self::DESCRIPTION] = $this->description;
            $result [self::ID] = $this->id;
            $result [self::IS_HIDDEN] = $this->isHidden;
            $result [self::NAME] = $this->name;
            $result [self::CITY] = $this->city;
            $result [self::COUNTRY] = $this->country; //print_r($result);
*/

            $params = $this->getFieldsList();

            foreach($params as $key => $value)
            {
                /*
                $value = self::camelCase($value);
                $k = 'Assay\Communication\Profile\Company::'.$key;
                $v = constant($k);
                $this->{$value} = Common::setIfExists($v, $namedValue, self::EMPTY_VALUE);
                */
                $key = self::camelCase($key);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
               // $this->{$key} = Common::setIfExists($v, $namedValue, self::EMPTY_VALUE);
                $result[$v] = $this->{$key};

            }

            return $result;
        }

        private function updateEntity():bool
        {
            /*
            $id[ISqlHandler::PLACEHOLDER] = ':ID';
            $id[ISqlHandler::VALUE] = $this->id;
            $id[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $name[ISqlHandler::PLACEHOLDER] = ':NAME';
            $name[ISqlHandler::VALUE] = $this->name;
            $name[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $description[ISqlHandler::PLACEHOLDER] = ':DESCRIPTION';
            $description[ISqlHandler::VALUE] = $this->description;
            $description[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $employersCount[ISqlHandler::PLACEHOLDER] = ':EMPLOYERS_COUNT';
            $employersCount[ISqlHandler::VALUE] = $this->employersCount;
            $employersCount[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
*/
            /*
            $city[ISqlHandler::PLACEHOLDER] = ':CITY';
            $city[ISqlHandler::VALUE] = $this->city;
            $city[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $country[ISqlHandler::PLACEHOLDER] = ':COUNTRY';
            $country[ISqlHandler::VALUE] = $this->country;
            $country[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
*/
            /*
            $is_hidden[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden[ISqlHandler::VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            */

            $sqlText = '';
            $params = $this->getFieldsList();
        //    $end_element = array_pop($params); //потому что после последнего элемента нам не надо ставить запятую

            foreach($params as $key => $value)
            {
                $propertyName = self::camelCase($key);
                //$varName = $key;
/*
                if($key == 'id'){
                    $id[ISqlHandler::PLACEHOLDER] = ':' . $value;
                    $id[ISqlHandler::VALUE] = $this->{$propertyName};
                    $id[ISqlHandler::DATA_TYPE] = constant('\PDO::' . $this->fieldTypes[$key]);//$this->{$propertyName};
                }
                else {
                    */
                    $$key[ISqlHandler::PLACEHOLDER] = ':' . $value;
                    $$key[ISqlHandler::VALUE] = $this->{$propertyName};
                    $$key[ISqlHandler::DATA_TYPE] = constant('\PDO::' . $this->fieldTypes[$key]);//$this->{$propertyName};

                    $sqlText .= constant('self::' . $value) . " = " . $$key[ISqlHandler::PLACEHOLDER] . ", ";
               // }

                $arguments[ISqlHandler::QUERY_PARAMETER][] = $$key;
            }
            //убираем запятую после последнего элемента
            $sqlText = mb_substr(rtrim($sqlText), 0, -1).' ';


//print_r($arguments); echo($sqlText); return;
   //         echo($sqlText); return;
/*
            $arguments[ISqlHandler::QUERY_TEXT] = '
                UPDATE 
                    '.$this->tablename.'
                SET
                    '.self::NAME.' = '.$name[ISqlHandler::PLACEHOLDER].', '.self::DESCRIPTION.' = '.$description[ISqlHandler::PLACEHOLDER].', 
                    update_date = now(), '.self::IS_HIDDEN.' = '.$is_hidden[ISqlHandler::PLACEHOLDER].', '.self::EMPLOYERS_COUNT.' = '.$employersCount[ISqlHandler::PLACEHOLDER].'
                WHERE 
                '.self::ID.' = '.$id[ISqlHandler::PLACEHOLDER];
            */
           // $arguments[ISqlHandler::QUERY_PARAMETER] = [$id,$name,$description,$is_hidden, $employersCount];
         //   print_r($arguments);

            $arguments[ISqlHandler::QUERY_TEXT] = '
               UPDATE 
                    '.$this->tablename.'
                SET 
                    '.$sqlText.'
                 WHERE
                    '.self::ID.' = '.$id[ISqlHandler::PLACEHOLDER];

         //   print_r($arguments[ISqlHandler::QUERY_TEXT]);
         //   return;


            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments); print_r($response);

            $isSuccessfulRequest = SqlHandler::isNoError($response);
            return $isSuccessfulRequest;
        }

        //проверяем, существуют ли для этого пользователя компания и объявления, если существуют, то выводим их
        //если не существуют, то возвращаем false

        public function getProfileCompany():bool
        {

        }

        public function getProfileAdvert():bool
        {

        }

        /*
            public function readEntity(string $id):bool
        {
            $id_field[ISqlHandler::PLACEHOLDER] = ':ID';
            $id_field[ISqlHandler::VALUE] = $id;
            $id_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            $is_hidden[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden[ISqlHandler::VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] ='
                SELECT 
                    *
                FROM 
                    '.$this->tablename.'
                WHERE 
                    '.self::IS_HIDDEN.'='.$is_hidden[ISqlHandler::PLACEHOLDER].' AND 
                    '.self::ID.'='.$id_field[ISqlHandler::PLACEHOLDER].'
            ';
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$is_hidden,$id_field];
            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); //print_r($response); exit;
            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            $result = $record != Common::EMPTY_ARRAY;

            return $result;
        }
        */

        }
}