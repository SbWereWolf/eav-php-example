<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.01.2017
 * Time: 20:12
 */

namespace Assay\DataAccess;

use Assay\Core\Configuration;

class DbCredentials implements IDbCredentials
{
    const DB_NAME_PARAMETER = 'dbname';
    const DB_HOST_PARAMETER = 'host';

    /**
     * @return array
     */
    public static function getDbReader():array
    {
        $dbReaderConfiguration = new Configuration(DB_READ_CONFIGURATION);
        
        $readerCredentials = self::parseDbCredentials($dbReaderConfiguration);

        return $readerCredentials;
    }

    /**
     * @return array
     */
    public static function getDbWriter():array
    {
        $dbWriterConfiguration = new Configuration(DB_WRITE_CONFIGURATION);

        $writerCredentials = self::parseDbCredentials($dbWriterConfiguration);

        return $writerCredentials;
    }


    /**
     * @param Configuration $dbConfiguration
     * @return array
     */
    private static function parseDbCredentials(Configuration $dbConfiguration):array
    {
        $dbCredentials = $dbConfiguration->getDbCredentials();
        
        $pdoDriver = $dbCredentials[Configuration::PDO_DBMS];
        $dbName = $dbCredentials[Configuration::DB_NAME];
        $dbHost = $dbCredentials[Configuration::DB_HOST];
        $dbAddress = $pdoDriver
            . ':'
            . self::DB_NAME_PARAMETER
            . '='
            . $dbName
            . ';'
            . self::DB_HOST_PARAMETER
            . '='
            . $dbHost;

        $dbLogin = $dbCredentials[Configuration::DB_LOGIN];
        $dbPassword = $dbCredentials[Configuration::DB_PASSWORD];

        $credentials[self::DATA_SOURCE_NAME] = $dbAddress;
        $credentials[self::LOGIN] = $dbLogin;
        $credentials[self::PASSWORD] = $dbPassword;
        return $credentials;
    }
}


