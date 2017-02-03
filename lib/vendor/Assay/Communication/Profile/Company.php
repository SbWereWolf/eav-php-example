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

    class Company extends \Assay\Core\Entity implements ICompany
    {
        /** @var string константа для не пустого значения */
        const EMPTY_VALUE = NULL;

        /** @var string имя таблицы */
        const TABLE_NAME = 'company';

        /** @var string разделитель слов в названиях */
        const WORD_DIVIDER = '_';

        /** @var string колонка идентификатора */
        const ID = 'id';
        const NAME = 'name';
        const DESCRIPTION = 'description';
        const EMPLOYERS_COUNT = 'employers_count';
        const SPHERE = 'sphere';
        const OTHER_CRITERIA = 'other_criteria';
        const IS_SUPPLIER = 'is_supplier';
        const IS_TRANSPORT = 'is_transport';
        const ADDRESS = 'address';
        const WEBSITE = 'website';
        const EMAIL = 'email';
        const PHONE = 'phone';
        const WORKTIME = 'worktime';

        const INN = 'inn';
    //    const REGISTRATION_DATE = 'registration_date';
     //   const TAX_REGISTRATION_DATE = 'tax_registration_date';
     //   const CONFIRM_REGISTRATION_DATE = 'confirm_registration_date';

        /** @var string идентификатор записи таблицы */
        public $id = self::EMPTY_VALUE;
        /** @var string признак "является скрытым" */
        public $isHidden = Core\IPrimitiveData::DEFAULT_IS_HIDDEN;
        /** @var string родительский элемент */
      //  public $parent = self::EMPTY_VALUE;
        /** @var string наименование */
        public $name = self::EMPTY_VALUE;
        /** @var string описание */
        public $description = self::EMPTY_VALUE;
        public $employersCount = self::EMPTY_VALUE;
        public $sphere = self::EMPTY_VALUE;
        public $otherCriteria = self::EMPTY_VALUE;
        public $isSupplier = self::EMPTY_VALUE;
        public $isTransport = self::EMPTY_VALUE;
        public $address = self::EMPTY_VALUE;
        public $website = self::EMPTY_VALUE;
        public $email = self::EMPTY_VALUE;
        public $phone = self::EMPTY_VALUE;
        public $worktime = self::EMPTY_VALUE;
        public $inn = self::EMPTY_VALUE;
        public $profileId = self::EMPTY_VALUE;
     //   public $registrationDate = self::EMPTY_VALUE;
    //    public $taxRegistrationDate = self::EMPTY_VALUE;
     //   public $confirmRegistrationDate = self::EMPTY_VALUE;

        protected $tablename = self::TABLE_NAME;

        public $fieldTypes = [
            'id' => 'PARAM_INT',
            'is_hidden' => 'PARAM_INT',
            'name' => 'PARAM_STR',
            'description' => 'PARAM_STR',
            'employers_count' => 'PARAM_INT',
            'sphere' => 'PARAM_STR',
            'other_criteria' => 'PARAM_STR',
            'is_supplier' => 'PARAM_INT',
            'is_transport' => 'PARAM_INT',
            'address' => 'PARAM_STR',
            'website' => 'PARAM_STR',
            'email' => 'PARAM_STR',
            'phone' => 'PARAM_STR',
            'worktime' => 'PARAM_STR',
            'inn' => 'PARAM_STR'/*,
            'registration_date' => 'PARAM_STR',
            'tax_registration_date' => 'PARAM_STR',
            'confirm_registration_date' => 'PARAM_STR'*/
            ];

       // public $profile; //- это id

        public function __construct($id, $profileId)
        {
            $this->id = $id;
            $this->profileId = $profileId;
            $this->getCurrentCompanyProfileData();
        }

        //проверяем, привязана ли эта компания к пользователю и может ли он ее редактировать
        public function isUserCompany():bool
        {
            $oneParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::VALUE] = $this->profileId;
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

            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                if (count($record) > 0) return true;
            }

            return false;
        }

        //получаем список полей таблицы
        public function getFieldsList():array
        {
            $refl = new \ReflectionClass('Assay\Communication\Profile\Company');
            $params = $refl->getConstants();
            $strangeParams = [];
            foreach ($params as $key => $value)
            {
                if(is_array($value)) continue;
                $stringKey = (string) $key;
                $stringValue = (string) $value;
                $strangeParams[$stringValue] = $stringKey;
            }
           // $strangeParams = array_flip($params);
            /*
            unset($params["TABLE_NAME"]);
            unset($params["EMPTY_VALUE"]);
            unset($params["DEFINE_AS_HIDDEN"]);
            unset($params["DEFINE_AS_NOT_HIDDEN"]);
            unset($params["DEFAULT_IS_HIDDEN"]);
            unset($params["WORD_DIVIDER"]);

            return $params;
            */
            unset($strangeParams[self::TABLE_NAME]);
            unset($strangeParams[self::EMPTY_VALUE]);
            unset($strangeParams[self::EMPTY_OBJECT]);
            unset($strangeParams[self::DEFINE_AS_HIDDEN]);
            unset($strangeParams[self::DEFINE_AS_NOT_HIDDEN]);
            unset($strangeParams[self::DEFAULT_IS_HIDDEN]);
            unset($strangeParams[self::WORD_DIVIDER]);
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


        //получаем профиль компании
        public function getCurrentCompanyProfileData():bool
        {
            $profileId = $this->profileId;
            $result = false;
            return $this->loadById($this->id);
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

            //теоретически, здесь еще надо проверять, есть такая компания или нет, чтоб не давать возможности
            //создать ее снова. Ну или прикрепить снова.


            $params = $this->getFieldsList();

            foreach($params as $key => $value)
            {
                //print_r($key);
                if($key == 'id') continue;
                $keyCamel = self::camelCase($key);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
                if(isset($values[$key]))
                    $this->{$keyCamel} = $values[$key];
                else $this->{$keyCamel} = self::EMPTY_VALUE;//Common::setIfExists($v, $values[$key], self::EMPTY_VALUE);
                // print_r($key);
            }

            $this->addEntity();
            $this->mutateEntity();




            //привязываем профиль компании к пользователю----------

            $oneParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::VALUE] = $this->id;
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $twoParameter[ISqlHandler::PLACEHOLDER] = ':PROFILE_ID';
            $twoParameter[ISqlHandler::VALUE] = $this->profileId;
            $twoParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

             $arguments[ISqlHandler::QUERY_TEXT] =
                'INSERT INTO profile_company(profile_id, company_id) '
                .' VALUES('. $twoParameter[ISqlHandler::PLACEHOLDER].','.$oneParameter[ISqlHandler::PLACEHOLDER].');
 
';
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$twoParameter, $oneParameter];

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); print_r($response);

            if($isSuccessfulRead = SqlHandler::isNoError($response)) $result = true;

            //------------------------------------------------------

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
                if(isset($values[$key]) && !empty($values[$key]))
                    $this->{$keyCamel} = $values[$key];
                else {
                    if($this->fieldTypes[$key] == 'PARAM_INT')  $this->{$keyCamel} = 0;
                    else $this->{$keyCamel} = self::EMPTY_VALUE;

                }//self::EMPTY_VALUE;//Common::setIfExists($v, $values[$key], self::EMPTY_VALUE);
               // print_r($key);
            }
//print_r($this); //$a = NULL; echo("sdfdsfsdf".$a);
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

            $stored = new Company($this->id, $this->profileId);
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
                $keyCamel = self::camelCase($key);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
               // $this->{$key} = Common::setIfExists($v, $namedValue, self::EMPTY_VALUE);
                $result[$v] = $this->{$keyCamel};

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
                //if($key == 'is_hidden') continue;
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
                    if( $this->fieldTypes[$key] == 'PARAM_INT')
                        $$key[ISqlHandler::VALUE] = intval($this->{$propertyName});
                    else
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


        /** Добавляет запись в БД
         * @return bool успешность изменения
         */
        /*
        public function addEntity():bool
        {
            $arguments[ISqlHandler::QUERY_TEXT] =
                ' INSERT INTO ' . $this->tablename
                . ' DEFAULT VALUES RETURNING '
                . self::ID
                .' ; '
            ;
            $sqlWriter = new SqlHandler(ISqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);

            $isSuccessfulRead = SqlHandler::isNoError($response);
            $record = self::EMPTY_ARRAY;
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
            }

            $this->id = Common::setIfExists(self::ID, $record, self::EMPTY_VALUE);
//            $this->isHidden = Common::setIfExists(self::IS_HIDDEN, $record, self::EMPTY_VALUE);

            $result = $this->id != self::EMPTY_VALUE;

            return $result;
        }
        */

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