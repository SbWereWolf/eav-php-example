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

    class Profile extends \Assay\Core\NamedEntity implements IProfile
    {
        /** @var string константа для не пустого значения */
        const EMPTY_VALUE = NULL;

        /** @var string разделитель слов в названиях */
        const WORD_DIVIDER = '_';

        /** @var string имя таблицы */
        const TABLE_NAME = 'profile';

        /** @var string колонка идентификатора */
        const ID = 'id';

        const COUNTRY = 'country';
        const CITY = 'city';

        /** @var string идентификатор записи таблицы */
       public $id = self::EMPTY_VALUE;
        /** @var string признак "является скрытым" */
        public $isHidden = Core\IPrimitiveData::DEFAULT_IS_HIDDEN;
        /** @var string родительский элемент */
      //  public $parent = self::EMPTY_VALUE;
        /** @var string код записи */
        public $code = self::EMPTY_VALUE;
        /** @var string наименование */
        public $name = self::EMPTY_VALUE;
        /** @var string описание */
        public $description = self::EMPTY_VALUE;
        public $email = self::EMPTY_VALUE;
        public $country = self::EMPTY_VALUE;
        public $city = self::EMPTY_VALUE;
        public $company = []; //empty array
        public $ad = [];
        public $insertDate = self::EMPTY_VALUE;

        protected $tablename = self::TABLE_NAME;

        public $fieldTypes = [
            'id' => 'PARAM_INT',
            'is_hidden' => 'PARAM_INT',
            'name' => 'PARAM_STR',
            'description' => 'PARAM_STR',
            'city' => 'PARAM_STR',
            'country' => 'PARAM_STR',
            'code' => 'PARAM_STR',
         ];


        public function __construct($id)
        {
            $this->id = $id;
            $this->getMode();
            $this->getCurrentUserProfileData();
            $this->getUserEmail();
            $this->getProfileCompany();
        }

        //получаем список полей таблицы
        public function getFieldsList():array
        {
            $refl = new \ReflectionClass('Assay\Communication\Profile\Profile');
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
            unset($strangeParams[self::GREETINGS_ROLE]);
            unset($strangeParams[self::EXTERNAL_ID]);
            //print_r($strangeParams);

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


        // public $profile; //- это id

        /** для капчи
         * @return bool
         */
        public function getCommentEnableArea():bool
        {
        }

        /** Имя для приветствия - показывается в форме отображения Профиля. И, наверное, в приветствии при заходе.
         * задается пользователем в настройках.
         * @return string
         */
        public function getGreetingsRole(): string
        {
            $result = $this->code;
            return $result;
        }

        /**
         * определяем роль - пользователь или компания
         * @return string
         */
        public function getMode(): bool
        {
            $this->mode = "user";
            return true;
        }


        public function isOwnProfile():bool
        {
            $ownProfile = 1;
            if($this->id != $ownProfile) return false;
            return true;
            //смотрим, какие у нашего пользователя вообще есть права, мб его вообще надо слать лесом
        }


        public function getUserEmail():bool
        {
            //интересно, откуда здесь брать мыло, если нам в сессии дадут только id профиля?
            //в общем, что-то тут делаем и получаем мыло
            $this->email = 'account@email';
            return true;
        }

        //получаем профиль текущего пользователя
        public function getCurrentUserProfileData():bool
        {
            //$session = new Assay\Permission\Privilege\Session();

            //  $profileId = $session->profile;
              $profileId = $this->id;
            //$profileId = 1; //для тестов
            $result = false;
           // if($this->loadById($profileId)) $result = true;
             return $this->loadById($profileId);
        }

        //проверяем, гостевой профиль у пользователя или нет
        public function checkIsUserGuest():bool
        {
           $isGuest = false;
           if($this->code == 'guest') $isGuest = true;
           return $isGuest;
        }

        public function loadById(string $id):bool
        {
            $oneParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::VALUE] = intval($id);
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $params = $this->getFieldsList(); //print_r($params);

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
            */
            /*
            parent::setByNamedValue($namedValue); //print_r($namedValue);
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

        //создаем профиль пользователя
        public function addUserProfileData(array $values):bool
        {
            //$session = new Assay\Permission\Privilege\Session();

            //  $profileId = $session->profile;
            //$profileId = 1; //для тестов
            //$profileId = $this->id;
            $result = false;
/*
            $this->update_date = time();
            $this->name = $name;
            $this->isHidden = self::IS_HIDDEN;
            $this->code = $code;
            $this->description = $description;
            $this->city = $city;
            $this->country = $country;

             $this->addEntity();
            $result = $this->mutateEntity();
*/

          //  if($this->mutateEntity() && $this->loadById($this->id))
          //      $result = true;
            //           print_r($result);

            $params = $this->getFieldsList();

            foreach($params as $key => $value)
            {
                if($key == 'id') continue;
                //print_r($key);
                $keyCamel = self::camelCase($key);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
                if(isset($values[$key]))
                    $this->{$keyCamel} = $values[$key];
                else $this->{$keyCamel} = self::EMPTY_VALUE;//Common::setIfExists($v, $values[$key], self::EMPTY_VALUE);
                // print_r($key);
            }

            $this->addEntity();
            $result = $this->mutateEntity();

            return $result;

            // if($this->loadById($profileId)) $result = true;




            // return $this->loadById($profileId);
        }

        //обновляем профиль текущего пользователя
        public function setCurrentUserProfileData(array $values):bool
        {
            //$session = new Assay\Permission\Privilege\Session();

            //  $profileId = $session->profile;
            //$profileId = 1; //для тестов
            //$profileId = $this->id;
            $result = false;

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

            $params = $this->getFieldsList();

            foreach($params as $key => $value)
            {
                //print_r($key);
               // if($key == 'id') continue; //чтоб не было возможности менять чужой профиль
                $keyCamel = self::camelCase($key);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
                if(isset($values[$key]) && !empty($values[$key]))
                    $this->{$keyCamel} = $values[$key];
                else {
                    if($this->fieldTypes[$key] == 'PARAM_INT')  $this->{$keyCamel} = self::EMPTY_VALUE;
                    else $this->{$keyCamel} = self::EMPTY_VALUE;

                }//self::EMPTY_VALUE;//Common::setIfExists($v, $values[$key], self::EMPTY_VALUE);
                // print_r($key);
            }

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

            $stored = new Profile($this->id);
            //$this->id = 1;
            $wasReadStored = $stored->loadById($this->id);

            $storedEntity = array();
            $entity = array();
            if ($wasReadStored) {
                $storedEntity = $stored->toEntity();
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
            $result = array();
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
                $keyCamel = self::camelCase($key);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
                // $this->{$key} = Common::setIfExists($v, $namedValue, self::EMPTY_VALUE);
                $result[$v] = $this->{$keyCamel};

            }

            return $result;
        }

        protected function updateEntity():bool
        {

            /*
            $id[ISqlHandler::PLACEHOLDER] = ':ID';
            $id[ISqlHandler::VALUE] = $this->id;
            $id[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $name[ISqlHandler::PLACEHOLDER] = ':NAME';
            $name[ISqlHandler::VALUE] = $this->name;
            $name[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $code[ISqlHandler::PLACEHOLDER] = ':CODE'; //- теоретически, он у нас не может меняться. Практически - сказали ни в чем себя не ограничивать.
            $code[ISqlHandler::VALUE] = $this->code;
            $code[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $description[ISqlHandler::PLACEHOLDER] = ':DESCRIPTION';
            $description[ISqlHandler::VALUE] = $this->description;
            $description[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $city[ISqlHandler::PLACEHOLDER] = ':CITY';
            $city[ISqlHandler::VALUE] = $this->city;
            $city[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $country[ISqlHandler::PLACEHOLDER] = ':COUNTRY';
            $country[ISqlHandler::VALUE] = $this->country;
            $country[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

            $is_hidden[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden[ISqlHandler::VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] = '
                UPDATE 
                    '.$this->tablename.'
                SET 
                    '.self::NAME.' = '.$name[ISqlHandler::PLACEHOLDER].', '.self::DESCRIPTION.' = '.$description[ISqlHandler::PLACEHOLDER].', 
                    '.self::CITY.' = '.$city[ISqlHandler::PLACEHOLDER].', '.self::COUNTRY.' = '.$country[ISqlHandler::PLACEHOLDER].',
                    update_date = now(), '.self::CODE.' = '.$code[ISqlHandler::PLACEHOLDER].', '.self::IS_HIDDEN.' = '.$is_hidden[ISqlHandler::PLACEHOLDER].'
                WHERE 
                '.self::ID.' = '.$id[ISqlHandler::PLACEHOLDER];
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$id,$name,$description,$city,$country,$is_hidden];
            */
         //   print_r($arguments);

            $sqlText = '';
            $params = $this->getFieldsList();
            //    $end_element = array_pop($params); //потому что после последнего элемента нам не надо ставить запятую

            foreach($params as $key => $value)
            {
                $propertyName = self::camelCase($key);
                if($key == 'is_hidden') continue;

                $$key[ISqlHandler::PLACEHOLDER] = ':' . $value;
                $$key[ISqlHandler::VALUE] = $this->{$propertyName};
                $$key[ISqlHandler::DATA_TYPE] = constant('\PDO::' . $this->fieldTypes[$key]);//$this->{$propertyName};

                $sqlText .= constant('self::' . $value) . " = " . $$key[ISqlHandler::PLACEHOLDER] . ", ";
                // }

                $arguments[ISqlHandler::QUERY_PARAMETER][] = $$key;
            }
            //убираем запятую после последнего элемента
            $sqlText = mb_substr(rtrim($sqlText), 0, -1).' ';

            $arguments[ISqlHandler::QUERY_TEXT] = '
               UPDATE 
                    '.$this->tablename.'
                SET 
                    '.$sqlText.'
                 WHERE
                    '.self::ID.' = '.$id[ISqlHandler::PLACEHOLDER];

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments); //print_r($response);

            $isSuccessfulRequest = SqlHandler::isNoError($response);
            return $isSuccessfulRequest;
        }

        //проверяем, существуют ли для этого пользователя компания и объявления, если существуют, то выводим их
        //если не существуют, то возвращаем false

        public function getProfileCompany():bool
        {
            $oneParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::VALUE] = $this->id;
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT company_id FROM profile_company'
                . ' WHERE profile_id '
                . ' = '
                . $oneParameter[ISqlHandler::PLACEHOLDER]
                . ' ORDER BY company_id DESC
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); //print_r($response);

            $isSuccessfulRead = SqlHandler::isNoError($response);


            $recordCompany = Core\ICommon::EMPTY_ARRAY;

            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                if(count($record) > 0){
                    //если что-то нашли в таблице связей, ищем название компании
                    $twoParameter[ISqlHandler::PLACEHOLDER] = ':COMPANY_ID';
                    $twoParameter[ISqlHandler::VALUE] = $record["company_id"];
                    $twoParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
                    $arguments = [];
                    $arguments[ISqlHandler::QUERY_TEXT] =
                        'SELECT '.Company::ID.','.Company::NAME.' FROM '.Company::TABLE_NAME
                        . ' WHERE '.Company::ID
                        . ' = '
                        . $twoParameter[ISqlHandler::PLACEHOLDER]
                        . '  LIMIT 1
;
';
                    $arguments[ISqlHandler::QUERY_PARAMETER][] = $twoParameter;

                    $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
                    $response = $sqlReader->performQuery($arguments); //print_r($response);

                    $isSuccessfulRead = SqlHandler::isNoError($response);

                    $recordCompany = [];
                    if ($isSuccessfulRead) {
                        $recordCompany = SqlHandler::getFirstRecord($response); //print_r($record);
                        $this->company = $recordCompany;
                    }
                }
                //$this->setByNamedValue($record);
            }

            $result = false;
            if ($recordCompany != array()) {
                $result = true;
            }

            return $result;
        }

        public function getProfileAdvert():bool
        {
            return false;
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