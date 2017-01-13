<?php

use Assay\DataAccess\SqlHandler;
use Assay\DataAccess\DbCredentials;

define('CONFIGURATION_ROOT', realpath(__DIR__ . DIRECTORY_SEPARATOR . 'configuration'));
define('DB_READ_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_read.ini');
define('DB_WRITE_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_write.ini');

/**
 * @param $className string Class to load
 */
function autoload($className)
{
    $path = __DIR__ . "/lib/vendor/";
    $className = ltrim($className, '\\');
    $fileName = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    $classSource = ($path . $fileName);
    require($classSource);
}

spl_autoload_register('autoload');

$credentials = DbCredentials::getDbReader();
$sqlReader = new SqlHandler($credentials);

$oneParameter[SqlHandler::QUERY_PLACEHOLDER] = ':CIFRI';
$oneParameter[SqlHandler::QUERY_VALUE] = '1351351';
$oneParameter[SqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;

$otherParameter[SqlHandler::QUERY_PLACEHOLDER] = ':BUKVI';
$otherParameter[SqlHandler::QUERY_VALUE] = 'dthgfhh';
$otherParameter[SqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;

$arguments[SqlHandler::QUERY_TEXT] =
    'select 564 AS RESULT, '
    . $oneParameter[SqlHandler::QUERY_PLACEHOLDER]
    . '::int AS CIFRI,'
    . $otherParameter[SqlHandler::QUERY_PLACEHOLDER]
    . '::text AS BUKVI'
;

$arguments[SqlHandler::QUERY_PARAMETER][] = $oneParameter;
$arguments[SqlHandler::QUERY_PARAMETER][] = $otherParameter;

$result = $sqlReader->performQuery($arguments);
echo '<pre>';
var_dump($arguments);
var_dump($result);
echo '</pre>';
