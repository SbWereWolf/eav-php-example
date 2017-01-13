<?php

use Assay\DataAccess\SqlReader;

define('CONFIGURATION_ROOT', realpath(__DIR__.DIRECTORY_SEPARATOR.'configuration'));
define('DB_READ_CONFIGURATION', CONFIGURATION_ROOT.DIRECTORY_SEPARATOR.'db_read.ini');

/**
 * @param $className string Class to load
 */
function autoload($className)
{
    $path = __DIR__ . "/lib/vendor/";
    $className = ltrim($className, '\\');
    $fileName  = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    $classSource = ($path.$fileName);
    require ($classSource);
}

spl_autoload_register('autoload');
echo DB_READ_CONFIGURATION;
$dbReadeConfiguration = new \Assay\Core\Configuration(DB_READ_CONFIGURATION);
$credentials = $dbReadeConfiguration->getCredentials();
// var_dump($credentials);
$sqlReader = new SqlReader();

$oneParameter[SqlReader::QUERY_PLACEHOLDER] = ':VALUE';
$oneParameter[SqlReader::QUERY_VALUE] = '1351351';
$oneParameter[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;


$arguments[SqlReader::QUERY_TEXT] = 'select 0 AS RESULT, '.$oneParameter[SqlReader::QUERY_PLACEHOLDER].'::int AS VALUE';

$arguments[SqlReader::QUERY_PARAMETER][] = $oneParameter;

echo '<pre>';

$result = $sqlReader ->performQuery($arguments);
var_dump($result);
echo '</pre>';
