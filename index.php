<?php


use Assay\DataAccess\SqlHandler;
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

echo "<pre>";

echo " \n searchResult = Structure::search() \n";
$searchResult = Structure::search();
var_dump($searchResult);
echo " \n searchResult = Structure::search('same') \n";
$searchResult = Structure::search('same');
var_dump($searchResult);
echo " \n searchResult = Structure::search('same','code') \n";
$searchResult = Structure::search('same','code');
var_dump($searchResult);

$structureForMutate = new Structure();
echo " \n structureForMutate->mutateEntity() \n";
$mutateResult = $structureForMutate->mutateEntity();
var_export($mutateResult );echo " \n ";
var_export($structureForMutate);
echo " \n structureForMutate->loadByCode('29') \n";
$loadResult = $structureForMutate->loadByCode('29');
var_export($loadResult);echo " \n ";
echo " \n structureForMutate->description.='+M' \n";
$structureForMutate->description.='+M';
var_export($structureForMutate);
echo " \n structureForMutate->mutateEntity() \n";
$mutateResult = $structureForMutate->mutateEntity();
var_export($mutateResult );echo " \n ";
var_export($structureForMutate);

$structureForLoad = new Structure();
echo " \n structureForLoad->loadByCode('') \n";
$structureForLoad->loadByCode('');
var_export($structureForLoad);
echo " \n structureForLoad->loadByCode('29') \n";
$structureForLoad->loadByCode('29');
var_export($structureForLoad);

$structureForPartition = new Structure();
echo " \n structureForPartition->isPartition() \n";
$isPartition = $structureForPartition->isPartition();
var_export($isPartition);
echo " \n structureForPartition->isRubric() \n";
$isRubric = $structureForPartition->isRubric();
var_export($isRubric);
echo " \n readEntity('29') structureForPartition->isPartition() \n";
$isSuccess = $structureForPartition->readEntity('29');
$isPartition = $structureForPartition->isPartition();
var_export($isPartition);
echo " \n structureForPartition->isRubric() \n";
$isRubric = $structureForPartition->isRubric();
var_export($isRubric);
echo " \n readEntity('2') structureForPartition->isPartition() \n";
$isSuccess = $structureForPartition->readEntity('2');
$isPartition = $structureForPartition->isPartition();
var_export($isPartition);
echo " \n structureForPartition->isRubric() \n";
$isRubric = $structureForPartition->isRubric();
var_export($isRubric);

$structureForStored = new Structure();
echo " \n structureForStored->getStored() \n";
$structureForStored->getStored();
var_dump($structureForStored);

echo " \n readEntity('29') => structureForStored->getStored() \n";
$isSuccess = $structureForStored->readEntity('29');
$structureForStored->getStored();
var_dump($structureForStored);

echo " \n structureForPath->getPath() \n";
$structureForPath = new Structure();
$path = $structureForPath->getPath();
var_dump($path);

echo " \n readEntity(29)=>structureForPath->getPath() \n";
$isSuccess = $structureForPath->readEntity('29');
$path = $structureForPath->getPath();
var_dump($path);

echo " \n structure->getParent() \n";
$structureForParent = new Structure();
$parent = $structureForParent->getParent();
var_dump($parent);

echo " \n readEntity => structure->getParent() \n";
$structureForParent->readEntity('6');
$parent = $structureForParent->getParent();
var_dump($parent);

/*
echo " \n structure->getMap(code) \n";
$map = $structure::getMap('code');
var_dump($map);

echo " \n structure->getMap() \n";
$map = $structure::getMap();
var_dump($map);
*/


/*
$structureLinkage[IStructure::TABLE_NAME][IStructure::PARENT] = '7';

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
*/

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
    . '::text AS BUKVI';

$arguments[SqlHandler::QUERY_PARAMETER][] = $oneParameter;
$arguments[SqlHandler::QUERY_PARAMETER][] = $otherParameter;

$result = $sqlReader->performQuery($arguments);
echo '<pre>';
var_dump($result);
echo '</pre>';
