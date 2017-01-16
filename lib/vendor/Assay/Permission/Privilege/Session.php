<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:11
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Common;
    use Assay\Core\MutableEntity;
    use Assay\DataAccess\SqlReader;

    class Session extends MutableEntity implements ISession
    {
        /** @var string имя таблицы */
        const TABLE_NAME = 'session';

        public $cookies;

        public $key;
        public $companyFilter;
        public $mode;
        public $paging;
        public $userName;

        public $userId;

        public function __construct()
        {
            $this->cookies = Common::EMPTY_OBJECT;

            $this->key = ISession::EMPTY_VALUE;
            $this->companyFilter = ISession::EMPTY_VALUE;
            $this->mode = ISession::EMPTY_VALUE;
            $this->paging = ISession::EMPTY_VALUE;
            $this->userName = ISession::EMPTY_VALUE;

            $this->userId = ISession::EMPTY_VALUE;
        }

        /**
         * Используется для инициализации элементом массива, если элемент не задан, то выдаётся значение по умолчанию
         * @return string идентификатор добавленной записи БД
         */
        public function addEntity():string
        {
            $result = 0;
            $sqlReader = new SqlReader();
            $arguments[SqlReader::QUERY_TEXT] = "
                INSERT INTO 
                    ".self::TABLE_NAME." 
                    (
                      ".self::INSERT_DATE."
                    ) 
                VALUES 
                    (
                        now()
                    )
                RETURNING ".self::ID.";
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == '00000') {
                $rows = $result_sql[SqlReader::RECORDS];
                $result = (count($rows) > 0)?$rows[0][$this::ID]:$result;
            }

            return $result;
        }
        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool
        {
            $result = false;
            $storedData = $this->getStored();
            $entity = $this->toEntity();

            $needUpdate = false;
            foreach ($entity as $key => $column) {
                $isExist = array_key_exists($key, $storedData);
                $equal = true;
                if ($isExist) {
                    $equal = $column == $storedData[$key];
                }
                if (!$equal) {
                    $needUpdate = true;
                }
            }
            if ($needUpdate) {
                $sqlReader = new SqlReader();
                $id[SqlReader::QUERY_PLACEHOLDER] = ':ID';
                $id[SqlReader::QUERY_VALUE] = $this->id;
                $id[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
                $key[SqlReader::QUERY_PLACEHOLDER] = ':KEY';
                $key[SqlReader::QUERY_VALUE] = $this->key;
                $key[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
                $user_id[SqlReader::QUERY_PLACEHOLDER] = ':USER_ID';
                $user_id[SqlReader::QUERY_VALUE] = $this->userId;
                $user_id[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
                $arguments[SqlReader::QUERY_TEXT] = "
                    UPDATE 
                        ".self::TABLE_NAME." 
                    SET 
                        ".self::KEY."=".$key[SqlReader::QUERY_PLACEHOLDER].", ".self::USER_ID."=".$user_id[SqlReader::QUERY_PLACEHOLDER]."
                    WHERE 
                        ".self::ID."=".$id[SqlReader::QUERY_PLACEHOLDER]."
                ";
                $arguments[SqlReader::QUERY_PARAMETER] = [$id,$key,$user_id];
                $result_sql = $sqlReader ->performQuery($arguments);
                $result = ($result_sql[SqlReader::ERROR_INFO][0] == "00000")?true:false;
            }

            return $result;

        }

        public static function open(string $userId):array
        {
            $process = self::OPEN_PROCESS;
            $object = self::SESSION_OBJECT;
            $result = array();
            $userId = ($userId == ISession::EMPTY_VALUE)?1:$userId; //Для теста. Если пустое значение, ставим ID гостя

            $userRole = new UserRole($userId);
            $isAllow = $userRole->userAuthorization($process, $object);
            if ($isAllow) {
                $result[self::USER_ID] = $userId;
                $key = uniqid('', true);
                $result[self::KEY] = $key;

                $session = new Session();
                $id = $session->addEntity();
                $result[self::ID] = $id;

                $session->setByNamedValue($result);
                $session->mutateEntity();
            }
            return $result;
        }

        public function setByNamedValue(array $namedValues)
        {
            $this->key = Common::setIfExists(
                self::KEY, $namedValues, ISession::EMPTY_VALUE
            );
            $this->companyFilter = Common::setIfExists(
                self::COMPANY_FILTER, $namedValues, ISession::EMPTY_VALUE
            );
            $this->mode = Common::setIfExists(
                self::MODE, $namedValues, ISession::EMPTY_VALUE
            );
            $this->paging = Common::setIfExists(
                self::PAGING, $namedValues, ISession::EMPTY_VALUE
            );
            $this->userName = Common::setIfExists(
                self::USER_NAME, $namedValues, ISession::EMPTY_VALUE
            );
            $this->userId = Common::setIfExists(
                self::USER_ID, $namedValues, ISession::EMPTY_VALUE
            );
            $this->id = Common::setIfExists(
                self::ID, $namedValues, ISession::EMPTY_VALUE
            );
        }

        public function getStored():array
        {
            $result = array();

            $dbh = new \PDO("pgsql:dbname=".Common::DB_NAME.";host=".Common::DB_HOST, Common::DB_LOGIN, Common::DB_PASSWORD);
            $sth = $dbh->prepare("
                SELECT 
                   *
                FROM 
                  ".self::TABLE_NAME."
                WHERE 
                  ".self::ID."=:ID
            ");
            $sth->bindValue(':ID', $this->id, \PDO::PARAM_STR);
            $sth->execute();
            $rows = $sth->fetchAll(\PDO::FETCH_ASSOC);

            if (count($rows) > 0 and $sth->errorCode() == "00000"):
                $result = $rows[0];
            endif;

            return $result;
        }

        public function toEntity():array
        {
            $entity = [];
            $entity[self::ID] = $this->id;
            $entity[self::IS_HIDDEN] = $this->isHidden;
            $entity[self::KEY] = $this->key;
            $entity[self::COMPANY_FILTER] = $this->companyFilter;
            $entity[self::MODE] = $this->mode;
            $entity[self::PAGING] = $this->paging;
            $entity[self::USER_NAME] = $this->userName;
            $entity[self::USER_ID] = $this->userId;

            return $entity;
        }

        public function setByCookie(Cookie $cookies)
        {
            $this->cookies = $cookies;

            $this->key = $this->cookies->key;
            $this->companyFilter = $this->cookies->companyFilter;
            $this->mode = $this->cookies->mode;
            $this->paging = $this->cookies->paging;
            $this->userName = $this->cookies->userName;
        }

        public function close():bool
        {
            $result = $this->hideEntity();
            return $result;
        }
    }
}