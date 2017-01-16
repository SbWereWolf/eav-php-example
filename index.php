<?php

use Assay\DataAccess\SqlHandler;
use Assay\DataAccess\DbCredentials;
use Assay\InformationsCatalog\StructureInformation\IStructure;
use Assay\InformationsCatalog\StructureInformation\Structure;

define('CONFIGURATION_ROOT', realpath(__DIR__ . DIRECTORY_SEPARATOR . 'configuration'));
define('DB_READ_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_read.php');
define('DB_WRITE_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_write.php');

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

$structure = new Structure();

$structureLinkage[IStructure::TABLE_NAME][IStructure::PARENT] = '7';
echo "<pre>";
echo " \n new structure \n";
var_dump($structure);
$structure->addReadable($structureLinkage);
echo " \n structure->addReadable \n";
var_dump($structure);
$structure->hideEntity();
echo " \n structure->hideEntity \n";
var_dump($structure);
$otherStructure = new Structure();
$otherStructure->addReadable($structureLinkage);
echo " \n otherStructure->addReadable \n";
var_dump($otherStructure);
$sameStructure = new Structure();
$sameStructure->readEntity($otherStructure->id);
echo " \n sameStructure->readEntity \n";
var_dump($sameStructure);
$sameStructure->getStored();
echo " \n sameStructure->getStored \n";
var_dump($sameStructure);
echo "</pre>";

/* === */


$sqlReader = new SqlHandler(SqlHandler::DATA_READER);

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
/*var_dump($arguments);*/
var_dump($result);
echo '</pre>';
