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

class SqlHandler implements ISqlHandler
{
    const RECORDS = 'fetchAll';
    const ERROR_INFO = 'errorInfo';

    private $dataSource = Common::EMPTY_VALUE;
    private $dbLogin = Common::EMPTY_VALUE;
    private $dbPassword = Common::EMPTY_VALUE;

    public function __construct( array $dbCredentials)
    {
        $this->dataSource = $dbCredentials[IDbCredentials::DATA_SOURCE_NAME];
        $this->dbLogin = $dbCredentials[IDbCredentials::LOGIN];
        $this->dbPassword = $dbCredentials[IDbCredentials::PASSWORD];
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

                $placeholder = Common::setIfExists(self::QUERY_PLACEHOLDER, $queryParameter, $emptyValue);
                $value = Common::setIfExists(self::QUERY_VALUE, $queryParameter, $emptyValue);
                $dataType = Common::setIfExists(self::QUERY_DATA_TYPE, $queryParameter, $emptyValue);

                $isParametersEmpty = ($placeholder == $emptyValue) || ($value == $emptyValue) || ($dataType == $emptyValue);
                if (!$isParametersEmpty) {
                    $dbQuery->bindValue($placeholder, $value, $dataType);
                }
            }
        }
    }
}
