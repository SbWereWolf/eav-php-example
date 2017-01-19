<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.01.2017
 * Time: 21:07
 */

namespace Assay\DataAccess;


interface IDbCredentials
{
    const PDO_DSN_PREFIX = 'DSN_PREFIX';
    const DATA_SOURCE_NAME = 'DATA_SOURCE_NAME';
    const LOGIN = 'DB_USER_LOGIN';
    const PASSWORD = 'DB_USER_PASSWORD';

    public static function getDbReader():array;

    public static function getDbWriter():array;
}
