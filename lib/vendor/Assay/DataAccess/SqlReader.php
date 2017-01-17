<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.01.2017
 * Time: 14:17
 */

namespace Assay\DataAccess;

use Assay\Core\Common;
use Assay\Core\Configuration;

//include_once(DB_READ_CONFIGURATION);

class SqlReader implements ISqlReader
{
    private $credentials = array();
    const DB_NAME_PARAMETER = 'dbname';
    const DB_HOST_PARAMETER = 'host';

    const QUERY_TEXT = 'QUERY_TEXT';
    const QUERY_PARAMETER = 'QUERY_PARAMETER';
    const QUERY_PLACEHOLDER = 'QUERY_PLACEHOLDER';
    const QUERY_VALUE = 'QUERY_VALUE';
    const QUERY_DATA_TYPE = 'QUERY_DATA_TYPE';

    const RECORDS = 'fetchAll';
    const ERROR_INFO = 'errorInfo';

    private function initialization(string $settingFilename)
    {
        $dbReaderConfiguration = new Configuration($settingFilename);
        $this->credentials = $dbReaderConfiguration->getCredentials();
    }

    public function performQuery(array $arguments):array
    {
        $this->initialization(DB_READ_CONFIGURATION);
        $connection = $this->getConnection();
        $dbQuery = self::getPdoStatement($connection, $arguments);
        $this->bindParameterValue($dbQuery, $arguments);
        $dbQuery->execute();

        $records = $dbQuery->fetchAll();
        $errorInfo = $dbQuery->errorInfo();

        $result[self::RECORDS] = $records;
        $result[self::ERROR_INFO] = $errorInfo;

        return $result;
    }

    public static function getPdoStatement(\PDO $connection, array $parameters):\PDOStatement
    {
        $queryText = Common::setIfExists(self::QUERY_TEXT, $parameters, Common::EMPTY_VALUE);

        $statement = Common::EMPTY_OBJECT;
        if ($queryText != Common::EMPTY_VALUE) {
            $statement = $connection->prepare($queryText);
        }

        return $statement;
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        $dbName = $this->credentials[Configuration::DB_NAME];
        $dbHost = $this->credentials[Configuration::DB_HOST];
        $dbAddress = self::DB_NAME_PARAMETER
            . '='
            . $dbName
            . ';'
            . self::DB_HOST_PARAMETER
            . '='
            . $dbHost;

        $dbLogin = $this->credentials[Configuration::DB_LOGIN];
        $dbPassword = $this->credentials[Configuration::DB_PASSWORD];

        $connection = new \PDO(
            'pgsql:'
            . $dbAddress
            , $dbLogin
            , $dbPassword
        );
        return $connection;
    }

    /**
     * @param \PDOStatement $dbQuery
     * @param array $arguments
     * @internal param $emptyValue
     */
    public function bindParameterValue(\PDOStatement $dbQuery, array $arguments)
    {
        $emptyValue = Common::EMPTY_VALUE;

        $queryParameters = Common::setIfExists(self::QUERY_PARAMETER, $arguments, $emptyValue);

        $isArgumentsEmpty = $queryParameters == $emptyValue;
        if (!$isArgumentsEmpty) {
            foreach ($queryParameters as $queryParameter) {

                $placeholder = Common::setIfExists(self::QUERY_PLACEHOLDER, $queryParameter, $emptyValue);
                $value = Common::setIfExists(self::QUERY_VALUE, $queryParameter, $emptyValue);
                $dataType = Common::setIfExists(self::QUERY_DATA_TYPE, $queryParameter, $emptyValue);

                $isParametersEmpty = ($placeholder == $emptyValue) || /*($value == $emptyValue) ||*/ ($dataType == $emptyValue);
                if (!$isParametersEmpty) {
                    $dbQuery->bindValue($placeholder, $value, $dataType);
                }
            }
        }
    }
}
