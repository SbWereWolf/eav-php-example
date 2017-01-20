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

/*
echo " \n searchResult = Structure::search() \n";
$searchResult = Structure::search();
var_dump($searchResult);
echo " \n searchResult = Structure::search('same') \n";
$searchResult = Structure::search('same');
var_dump($searchResult);
echo " \n searchResult = Structure::search('same','code') \n";
$searchResult = Structure::search('same','code');
var_dump($searchResult);
echo " \n searchResult = Structure::search('','code') \n";
$searchResult = Structure::search('','code');
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
$isSuccess = $structureForPartition->loadById('29');
$isPartition = $structureForPartition->isPartition();
var_export($isPartition);
echo " \n structureForPartition->isRubric() \n";
$isRubric = $structureForPartition->isRubric();
var_export($isRubric);
echo " \n readEntity('2') structureForPartition->isPartition() \n";
$isSuccess = $structureForPartition->loadById('2');
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
$isSuccess = $structureForStored->loadById('29');
$structureForStored->getStored();
var_dump($structureForStored);

echo " \n structureForPath->getPath() \n";
$structureForPath = new Structure();
$path = $structureForPath->getPath();
var_dump($path);

echo " \n readEntity(29)=>structureForPath->getPath() \n";
$isSuccess = $structureForPath->loadById('29');
$path = $structureForPath->getPath();
var_dump($path);

echo " \n structure->getParent() \n";
$structureForParent = new Structure();
$parent = $structureForParent->getParent();
var_dump($parent);

echo " \n readEntity => structure->getParent() \n";
$structureForParent->loadById('6');
$parent = $structureForParent->getParent();
var_dump($parent);
*/

/*
$structureForMap = new Structure();
echo " \n structure->getMap(code) \n";
$map = $structureForMap::getMap('code');
var_dump($map);

echo " \n structure->getMap() \n";
$map = $structureForMap::getMap();
var_dump($map);
*/



$structureLinkage[Structure::PARENT] = '20';

$structureForParent = new Structure();
echo " \n structureForParent->addEntity() \n";
$structureForParent->addEntity();
var_dump($structureForParent);
echo " \n structureForParent->setParent \n";
$structureForParent->setParent($structureLinkage);
var_dump($structureForParent);
echo " \n structureForParent->hideEntity() \n";
$structureForParent->hideEntity();
var_dump($structureForParent);
$otherStructure = new Structure();
echo " \n otherStructure->addEntity() \n";
$otherStructure->addEntity();
var_dump($otherStructure);
echo " \n otherStructure->setParent \n";
$otherStructure->setParent($structureLinkage);
var_dump($otherStructure);
echo " \n otherStructure->code : otherStructure->addEntity() => $otherStructure->id \n";
$otherStructure->code=" otherStructure->addEntity() => $otherStructure->id";
var_dump($otherStructure);
echo " \n resultOfMutate = otherStructure->mutateEntity() \n";
$resultOfMutate = $otherStructure->mutateEntity();
var_dump($otherStructure);
var_dump($resultOfMutate );
$sameStructure = new Structure();
echo " \n sameStructure->loadById($otherStructure->id) \n";
$sameStructure->loadById($otherStructure->id);
var_dump($sameStructure);
echo " \n otherStructure->addEntity() => $otherStructure->id \n";
$sameStructure->description=" otherStructure->addEntity() => $otherStructure->id";
var_dump($sameStructure);
echo " \n sameStructure->getStored \n";
$sameStructure->getStored();
var_dump($sameStructure);

echo "</pre>";

/* === */


$sqlReader = new SqlHandler(SqlHandler::DATA_READER);

$oneParameter[SqlHandler::PLACEHOLDER] = ':CIFRI';
$oneParameter[SqlHandler::VALUE] = '1351351';
$oneParameter[SqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

$otherParameter[SqlHandler::PLACEHOLDER] = ':BUKVI';
$otherParameter[SqlHandler::VALUE] = 'dthgfhh';
$otherParameter[SqlHandler::DATA_TYPE] = \PDO::PARAM_STR;

$arguments[SqlHandler::QUERY_TEXT] =
    'select 564 AS RESULT, '
    . $oneParameter[SqlHandler::PLACEHOLDER]
    . '::int AS CIFRI,'
    . $otherParameter[SqlHandler::PLACEHOLDER]
    . '::text AS BUKVI';

$arguments[SqlHandler::QUERY_PARAMETER][] = $oneParameter;
$arguments[SqlHandler::QUERY_PARAMETER][] = $otherParameter;

$result = $sqlReader->performQuery($arguments);
echo '<pre>';
var_dump($result);
echo '</pre>';
