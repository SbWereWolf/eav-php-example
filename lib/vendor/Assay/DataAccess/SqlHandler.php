<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.01.2017
 * Time: 14:17
 */

namespace Assay\DataAccess;

use Assay\Core\Common;

class SqlHandler implements ISqlHandler
{
    const RECORDS = 'fetchAll';
    const ERROR_INFO = 'errorInfo';

    private $dataSource = Common::EMPTY_VALUE;
    private $dbLogin = Common::EMPTY_VALUE;
    private $dbPassword = Common::EMPTY_VALUE;

    public function __construct($type = self::DATA_READER)
    {
        $dbCredentials = array();
        switch ($type) {
            case self::DATA_READER :
                $dbCredentials = DbCredentials::getDbReader();
                break;
            case self::DATA_WRITER :
                $dbCredentials = DbCredentials::getDbWriter();
                break;
        }

        $this->dataSource = Common::setIfExists(
            IDbCredentials::DATA_SOURCE_NAME,
            $dbCredentials,
            Common::EMPTY_VALUE);
        $this->dbLogin = Common::setIfExists(
            IDbCredentials::LOGIN,
            $dbCredentials,
            Common::EMPTY_VALUE);
        $this->dbPassword = Common::setIfExists(
            IDbCredentials::PASSWORD,
            $dbCredentials,
            Common::EMPTY_VALUE);
    }


    /**
     * @param $response
     * @return bool
     */
    public static function isNoError($response):bool
    {
        $errorInfo = Common::setIfExists(self::ERROR_INFO,
            $response,
            Common::EMPTY_VALUE);

        $errorCode = Common::EMPTY_VALUE;
        $errorNumber = Common::EMPTY_VALUE;
        $errorMessage = Common::EMPTY_VALUE;
        if ($errorInfo != Common::EMPTY_VALUE) {
            $errorCode = $errorInfo[self::EXEC_ERROR_CODE_INDEX];
            $errorNumber = $errorInfo[self::EXEC_ERROR_NUMBER_INDEX];
            $errorMessage = $errorInfo[self::EXEC_ERROR_MESSAGE_INDEX];
        }
        $isSuccessfulRequest = false;
        if ($errorCode != Common::EMPTY_VALUE) {
            $isSuccessfulRequest = $errorCode == self::EXEC_WITH_SUCCESS_CODE
                && $errorNumber == self::EXEC_WITH_SUCCESS_NUMBER
                && $errorMessage == self::EXEC_WITH_SUCCESS_MESSAGE;
        }
        return $isSuccessfulRequest;
    }

    public function performQuery(array $arguments):array
    {
        $connection = new \PDO ($this->dataSource,
            $this->dbLogin,
            $this->dbPassword);
        $dbQuery = self::getPdoStatement($connection, $arguments);
        $this->bindParameterValue($dbQuery, $arguments);
        $dbQuery->execute();

        $records = $dbQuery->fetchAll(\PDO::FETCH_ASSOC);
        $errorInfo = $dbQuery->errorInfo();

        $result[self::RECORDS] = $records;
        $result[self::ERROR_INFO] = $errorInfo;

        return $result;
    }

    private static function getPdoStatement(\PDO $connection, array $parameters):\PDOStatement
    {
        $queryText = Common::setIfExists(self::QUERY_TEXT, $parameters, Common::EMPTY_VALUE);

        $statement = Common::EMPTY_OBJECT;
        if ($queryText != Common::EMPTY_VALUE) {
            $statement = $connection->prepare($queryText);
        }

        return $statement;
    }

    /**
     * @param \PDOStatement $dbQuery
     * @param array $arguments
     * @internal param $emptyValue
     */
    private function bindParameterValue(\PDOStatement $dbQuery, array $arguments)
    {
        $emptyValue = Common::EMPTY_VALUE;

        $queryParameters = Common::setIfExists(self::QUERY_PARAMETER, $arguments, $emptyValue);

        $isArgumentsEmpty = $queryParameters == $emptyValue;
        if (!$isArgumentsEmpty) {
            foreach ($queryParameters as $queryParameter) {

                $placeholder = Common::setIfExists(self::PLACEHOLDER, $queryParameter, $emptyValue);
                $value = Common::setIfExists(self::VALUE, $queryParameter, $emptyValue);
                $dataType = Common::setIfExists(self::DATA_TYPE, $queryParameter, $emptyValue);

                $isParametersEmpty = ($placeholder == $emptyValue) /*|| ($value == $emptyValue)*/ || ($dataType == $emptyValue);
                if (!$isParametersEmpty) {
                    $dbQuery->bindValue($placeholder, $value, $dataType);
                }
            }
        }
    }

    /**
     * @param $response
     * @return array
     */
    public static function getFirstRecord(array $response):array
    {
        $records = Common::setIfExists(self::RECORDS,
            $response,
            Common::EMPTY_VALUE);

        $responseValue = array();
        if ($records != Common::EMPTY_VALUE) {
            $responseIndex = 0;
            $responseValue = Common::setIfExists($responseIndex,
                $records,
                array());
        }
        return $responseValue;
    }

    public static function getAllRecords(array $response):array
    {
        $records = Common::setIfExists(self::RECORDS,
            $response,
            array());

        return $records;
    }

    public static function setBindParameter(\string $placeholder, \string $value, \int $dataType):array
    {
        $bindValue = $value;
        switch ($dataType) {
            case \PDO::PARAM_INT :
                $bindValue = intval($value);
                break;
            case \PDO::PARAM_STR:
                $bindValue = strval($value);
                break;
        }
        $result = [
            self::PLACEHOLDER => $placeholder,
            self::VALUE => $bindValue,
            self::DATA_TYPE => $dataType,
        ];

        return $result;
    }

    /** Прочитать все результаты запроса данных
     * @param $arguments array аргументы выборки данных
     * @return array данные выборки
     */
    public static function readAllRecords(array $arguments):array
    {
        $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
        $response = $sqlReader->performQuery($arguments);
        $resultCountChildren = SqlHandler::isNoError($response);

        $result = array();
        if ($resultCountChildren) {
            $result = SqlHandler::getAllRecords($response);
        }
        return $result;
    }

    /** Прочитать одну строку из выборки данных
     * @param $arguments array аргументы выборки данных
     * @return array результат чтения данных
     */
    public static function readOneRecord(array $arguments):array
    {
        $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
        $response = $sqlReader->performQuery($arguments);

        $isSuccessfulRead = SqlHandler::isNoError($response);

        $record = self::EMPTY_ARRAY;
        if ($isSuccessfulRead) {
            $record = SqlHandler::getFirstRecord($response);
        }

        return $record;
    }
    /** Сделать одну запись
     * @param $arguments array параметры записи
     * @return array записанные значения
     */
    public static function writeOneRecord($arguments):array
    {
        $sqlWriter = new SqlHandler(ISqlHandler::DATA_WRITER);
        $response = $sqlWriter->performQuery($arguments);

        $isSuccessfulRequest = SqlHandler::isNoError($response);

        $record = self::EMPTY_ARRAY;
        if ($isSuccessfulRequest) {
            $record = SqlHandler::getFirstRecord($response);

        }

        return $record;
    }

    /** Записать все строки
     * @param $arguments array аргументы записи
     * @return array результат записи
     */
    public static function writeAllRecords(array $arguments):array
    {
        $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
        $response = $sqlWriter->performQuery($arguments);

        $isSuccessfulDelete = SqlHandler::isNoError($response);

        $records = ISqlHandler::EMPTY_ARRAY;
        if ($isSuccessfulDelete) {
            $records = SqlHandler::getAllRecords($response);
        }
        
        return $records;
    }
}
