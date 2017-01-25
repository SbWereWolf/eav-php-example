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
        const WORD_DIVIDER = '-';

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
        const REGISTRATION_DATE = 'registration_date';
        const TAX_REGISTRATION_DATE = 'tax_registration_date';
        const CONFIRM_REGISTRATION_DATE = 'confirm_registration_date';

        /** @var string идентификатор записи таблицы */
        public $id = self::EMPTY_VALUE;
        /** @var string признак "является скрытым" */
        public $isHidden = Core\IEntity::DEFAULT_IS_HIDDEN;
        /** @var string родительский элемент */
      //  public $parent = self::EMPTY_VALUE;
        /** @var string наименование */
        public $name = self::EMPTY_VALUE;
        /** @var string описание */
        public $description = self::EMPTY_VALUE;
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
        public $registrationDate = self::EMPTY_VALUE;
        public $taxRegistrationDate = self::EMPTY_VALUE;
        public $confirmRegistrationDate = self::EMPTY_VALUE;

        protected $tablename = self::TABLE_NAME;

       // public $profile; //- это id

        //проверяем, привязана ли эта компания к пользователю и может ли он ее редактировать
        public function isUserCompany():bool
        {

        }

        //получаем список полей таблицы
        public function getFieldsList():array
        {
            $refl = new \ReflectionClass('Assay\Communication\Profile\Company');
            $params = $refl->getConstants();
            $strangeParams = [];
            foreach ($params as $key => $value)
            {
                $stringKey = (string) $key;
                $strangeParams[$value] = $stringKey;
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
            unset($strangeParams[self::DEFINE_AS_HIDDEN]);
            unset($strangeParams[self::DEFINE_AS_NOT_HIDDEN]);
            unset($strangeParams[self::DEFAULT_IS_HIDDEN]);
            unset($strangeParams[self::WORD_DIVIDER]);
            //print_r($strangeParams);

            return $strangeParams;
        }

        //camelCase
        public static function camelCase($str, array $noStrip = [])
        {
            // non-alpha and non-numeric characters become spaces
          //  $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
            $str = trim($str);
            // uppercase the first character of each word
            $str = ucwords($str, self::WORD_DIVIDER);
            $str = str_replace(" ", "", $str);
            $str = lcfirst($str);

            return $str;
        }


        //получаем профиль компании
        public function getCurrentCompanyProfileData():bool
        {
            $profileId = $this->id;
            $result = false;
            return $this->loadById($profileId);
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
                $key = self::camelCase($key);
                $k = 'Assay\Communication\Profile\Company::'.$value;
                $v = constant($k);
                $this->{$key} = Common::setIfExists($v, $namedValue, self::EMPTY_VALUE);

            }

            return true;
        }

        //обновляем профиль текущего пользователя
        public function setCurrentUserProfileData(string $name, int $isHidden, $code, string $description, string $city, string $country):bool
        {
            //$session = new Assay\Permission\Privilege\Session();

            //  $profileId = $session->profile;
            //$profileId = 1; //для тестов
            //$profileId = $this->id;
            $result = false;

            $this->update_date = time();
            $this->name = $name;
            $this->isHidden = $isHidden;
            $this->code = $code;
            $this->description = $description;
            $this->city = $city;
            $this->country = $country;

           // $this->addEntity();
            //$result = $this->mutateEntity();

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

            $stored = new Profile();
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

            $result [self::CODE] = $this->code;
            $result [self::DESCRIPTION] = $this->description;
            $result [self::ID] = $this->id;
            $result [self::IS_HIDDEN] = $this->isHidden;
            $result [self::NAME] = $this->name;
            $result [self::CITY] = $this->city;
            $result [self::COUNTRY] = $this->country; //print_r($result);

            return $result;
        }

        private function updateEntity():bool
        {

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
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$id,$name,$description,$city,$country/*,$is_hidden*/];
         //   print_r($arguments);

            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments); //print_r($response);

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