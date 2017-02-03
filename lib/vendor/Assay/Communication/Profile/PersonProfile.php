<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:39
 */
namespace Assay\Communication\Profile {

    use Assay\Core\NamedEntity;
    use Assay\Core;
    use \Assay\DataAccess\ISqlHandler;
    use \Assay\DataAccess\SqlHandler;
    use Assay\Permission\Privilege;

    class PersonProfile extends NamedEntity implements IPersonProfile
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
        const DEFAULT_IS_HIDDEN = false;

        /** @var string идентификатор записи таблицы */
        public $id = self::EMPTY_VALUE;
        /** @var string признак "является скрытым" */
        public $isHidden = self::DEFAULT_IS_HIDDEN;
        /** @var string родительский элемент */
        //  public $parent = self::EMPTY_VALUE;
        /** @var string код записи */
        public $code = self::EMPTY_VALUE;
        /** @var string наименование */
        public $name = self::EMPTY_VALUE;
        /** @var string описание */
        public $description = self::EMPTY_VALUE;
        //public $email = self::EMPTY_VALUE;
        public $country = self::EMPTY_VALUE;
        public $city = self::EMPTY_VALUE;
        //public $company = []; //empty array
        //public $ad = [];
        public $registrationDate = self::EMPTY_VALUE;

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
           // $this->getMode();
           // $this->getCurrentUserProfileData();
            $this->loadById($this->id);
           // $this->getUserEmail();
         //   $this->getProfileCompany();
        }

/*
        //проверяем, гостевой профиль у пользователя или нет
        public function checkIsUserGuest():bool
        {
            $isGuest = false;
            if($this->code == 'guest') $isGuest = true;
            return $isGuest;
        }
*/

        public function getForGreetings():string
        {

        }

        public function enableCommenting():bool
        {
        }

        public function purgeGroup():bool
        {
        }

        public function setGroup():bool
        {
        }

        //создаем профиль пользователя
        public function addProfile($accountId):bool
        {

            $result = false;
            $params = Common::getFieldsList(__CLASS__);

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
            if ($this->addEntity()) {
            //обновляем профиль, записываем как минимум дату создания
            $this->date = date('Y-m-d H:i:s');
            $result = $this->mutateEntity();

            //затем записываем связь в таблицу связи аккаунт-профиль
                //видимо, это тоже должно делаться как-то хитровыебано

            //  if($this->mutateEntity() && $this->loadById($this->id))
            //      $result = true;
            //           print_r($result);
            }
            return $result;

            // if($this->loadById($profileId)) $result = true;
            // return $this->loadById($profileId);
        }


        /** Добавляет запись в БД
         * @return bool успешность изменения
         */

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

        public function loadById(string $id):bool
        {
            $oneParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::VALUE] = intval($id);
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $params = Common::getFieldsList(__CLASS__); //print_r($params);

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
            $params = Common::getFieldsList(__CLASS__);

            foreach($params as $key => $value)
            {
                $keyCamel = Common::camelCase($key, [], self::WORD_DIVIDER);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
                $this->{$keyCamel} = Core\Common::setIfExists($v, $namedValue, self::EMPTY_VALUE);

            }

            return true;
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

            $storedEntity = [];
            $entity = [];
            if ($wasReadStored) {
                $storedEntity = $stored->toEntity();
                $entity = $this->toEntity(); //print_r($entity);
            }

            $isContain = Core\Common::isOneArrayContainOther($entity, $storedEntity);
            // if (!$isContain) {
            $result = $this->updateEntity();
            // }
//print_r($result);
            return $result;
        }

        public function toEntity():array
        {
            $result = array();
            $params = Common::getFieldsList(__CLASS__);

            foreach($params as $key => $value)
            {
                $keyCamel = Common::camelCase($key, [], self::WORD_DIVIDER);
                $k = __CLASS__.'::'.$value;
                $v = constant($k);
                // $this->{$key} = Common::setIfExists($v, $namedValue, self::EMPTY_VALUE);
                $result[$v] = $this->{$keyCamel};

            }

            return $result;
        }

        protected function updateEntity():bool
        {

            $sqlText = '';
            $params = Common::getFieldsList(__CLASS__);
            //    $end_element = array_pop($params); //потому что после последнего элемента нам не надо ставить запятую

            foreach($params as $key => $value)
            {
                $propertyName = Common::camelCase($key, [], self::WORD_DIVIDER);
                if($key == 'is_hidden') continue;

                $$key[ISqlHandler::PLACEHOLDER] = ':' . $value;
                $$key[ISqlHandler::VALUE] = $this->{$propertyName};
                $$key[ISqlHandler::DATA_TYPE] = constant('\PDO::' . $this->fieldTypes[$key]);//$this->{$propertyName};

                if($key != 'id')
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


        public function getAd():bool
        {
            return false;
        }
    }
}