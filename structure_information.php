<?php


use Assay\DataAccess\SqlHandler;
use Assay\InformationsCatalog\StructureInformation\Structure;
use Assay\InformationsCatalog\StructureInformation\Rubric;

include "autoloader.php";

echo '<pre>';

echo " \n rubric = new Rubric() \n";
$rubric = new Rubric();
var_dump($rubric );
echo " \n rubric->addEntity() \n";
$rubric->addEntity();
$rubricId= $rubric->id;
var_dump($rubric );
echo " \n rubric->code = $rubric->id \n";
$rubricCode = "rubric id $rubric->id";
$rubric->code = $rubricCode ;
$rubric->description= ' test add entity for rubric ';
$rubric->name = " rubric NAME $rubric->id ";
var_dump($rubric );
echo " \n rubric->getElementDescription() \n";
$rubricDescription = $rubric->getElementDescription();
var_dump($rubricDescription );
echo " \n rubric->toEntity() \n";
$rubricToEntity = $rubric->toEntity();
var_dump($rubricToEntity);
echo " \n rubricForNamedValues = new Rubric() \n";
$rubricForNamedValues = new Rubric();
var_dump($rubricForNamedValues);
echo " \n rubric->setByNamedValue(rubricToEntity)\n";
$rubricForNamedValues= $rubric->setByNamedValue($rubricToEntity);
var_dump($rubricForNamedValues);
echo " \n rubric before \n";
var_dump($rubric);
echo " \n rubric->mutateEntity() \n";
$rubricMutateResult = $rubric->mutateEntity();
var_dump($rubricMutateResult);
echo " \n rubric after \n";
var_dump($rubric);
echo " \n otherRubric = new Rubric() \n";
$otherRubric = new Rubric();
var_dump($otherRubric );
echo " \n otherRubric->loadByCode($rubricCode) \n";
$otherRubric->loadByCode($rubricCode);
var_dump($otherRubric );
echo " \n someRubric = new Rubric() \n";
$someRubric = new Rubric();
var_dump($someRubric);
echo " \n someRubric->loadById($rubricId) \n";
$someRubric->loadById($rubricId);
var_dump($someRubric);
echo " \n rubric->hideEntity() \n";
$rubric->hideEntity();
var_dump($rubric);
echo " \n rubricForGetStored = new Rubric() \n";
$rubricForGetStored = new Rubric();
var_dump($rubricForGetStored);
echo " \n rubricForGetStored->getStored() \n";
$rubricForGetStored->getStored();
var_dump($rubricForGetStored);
echo " \n rubricForGetStored->getStored() with id => $rubricId\n";
$rubricForGetStored->id = $rubricId;
$rubricForGetStored->getStored();
var_dump($rubricForGetStored);

echo '</pre>';

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

$structureForMap = new Structure();
echo " \n structure->getMap(code) \n";
$map = $structureForMap::getMap('code');
var_dump($map);

echo " \n structure->getMap() \n";
$map = $structureForMap::getMap();
var_dump($map);

$structureForParent = new Structure();
echo " \n structureForParent->addEntity() \n";
$structureForParent->addEntity();
var_dump($structureForParent);
echo " \n structureForParent->setParent \n";
$structureForParent->setParent('20');
var_dump($structureForParent);
echo " \n structureForParent->hideEntity() \n";
$structureForParent->hideEntity();
var_dump($structureForParent);
$otherStructure = new Structure();
echo " \n otherStructure->addEntity() \n";
$otherStructure->addEntity();
var_dump($otherStructure);
echo " \n otherStructure->setParent \n";
$otherStructure->setParent('20');
var_dump($otherStructure);
echo " \n otherStructure->code : otherStructure->addEntity() => $otherStructure->id \n";
$otherStructure->code=" otherStructure->addEntity() => $otherStructure->id";
var_dump($otherStructure);
echo " \n resultOfMutate = otherStructure->mutateEntity() \n";
$resultOfMutate = $otherStructure->mutateEntity();
var_dump($otherStructure);
var_dump($resultOfMutate );
echo " \n otherStructure->id =  $otherStructure->id \n";
$sameStructure = new Structure();
echo " \n sameStructure->loadById($otherStructure->id) \n";
$sameStructure->loadById($otherStructure->id);
var_dump($sameStructure);
echo " \n sameStructure->description => $otherStructure->id \n";
$sameStructure->description=" => $otherStructure->id";
var_dump($sameStructure);
echo " \n sameStructure->getStored \n";
$sameStructure->getStored();
var_dump($sameStructure);


echo "</pre>";

