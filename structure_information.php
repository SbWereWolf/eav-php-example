<?php

use Assay\InformationsCatalog\DataInformation\RubricPosition;
use Assay\InformationsCatalog\StructureInformation\InformationProperty;
use Assay\InformationsCatalog\StructureInformation\Rubric;

include "autoloader.php";


echo '<pre>';
/*
$RubricPosition = new RubricPosition();
var_dump($RubricPosition);
$InformationProperty= new InformationProperty();
var_dump($InformationProperty);
$RubricInformationProperty= new RubricInformationProperty();
var_dump($RubricInformationProperty);
*/

echo '</pre>';


echo '<pre>';
// Добавили рубрику
echo " \n --==@@ RUBRIC @@==-- ";
echo " \n rubric = new Rubric() \n";
$rubric = new Rubric();
var_dump($rubric);
echo " \n rubric->addEntity(); \n";
$result = $rubric->addEntity();
var_dump($rubric);
$rubricIdForRestore = $rubric->id;
echo " \n setup rubric code , name , desc for id => $rubric->id \n";
$rubric->code = " rubric CODE for $rubric->id";
$rubric->name = " rubric NAME for $rubric->id";
$rubric->description = " rubric DESCRIPTION for $rubric->id";
var_dump($rubric);
echo " \n rubric->mutateEntity(); \n";
$rubric->mutateEntity();
var_dump($rubric);
unset($rubric);
echo " \n rubricForRestore = new Rubric(); \n";
$rubricForRestore = new Rubric();
var_dump($rubricForRestore);
echo " \n rubricForRestore->id = $rubricIdForRestore; rubricForRestore->getStored(); \n";
$rubricForRestore->id = $rubricIdForRestore;
$isSuccess = $rubricForRestore->getStored();
var_dump($rubricForRestore);
// Добавили свойство
echo " \n --==@@ INFORMATION PROPERTY @@==-- ";
echo " \n property = new InformationProperty(); \n";
$property = new InformationProperty();
var_dump($property);
echo " \n property->addEntity(); \n";
$isSuccess = $property->addEntity();
var_dump($property);
echo " \n property->setInformationDomain('SYSTEM_LIKE')\n";
$property->setInformationDomain('SYSTEM_LIKE');
var_dump($property);
echo " \n property set : code name description isHidden ')\n";
$propertyId = $property->id;
$propertyCode = " property CODE $propertyId ";
$property->code = $propertyCode;
$property->name = " property NAME $propertyId ";
$property->description = " property DESCRIPTION $propertyId ";
$property->isHidden = InformationProperty::DEFINE_AS_NOT_HIDDEN;
var_dump($property);
echo " \n property->mutateEntity(); \n";
$isSuccess = $property->mutateEntity();
var_dump($property);
// добавили рубрике свойство
echo " \n --==@@ POSITION INFORMATION PROPERTY @@==-- ";
echo " \n propertyId = rubricForRestore->addProperty($propertyCode); \n";
$isSuccess = $rubricForRestore->addProperty($propertyCode);
var_dump($isSuccess);
echo " \n rubricForRestore->getProperties(); \n";
$rubricProperties = $rubricForRestore->getProperties();
var_dump($rubricProperties);
// Добавили позицию
echo " \n --==@@ RUBRIC POSITION @@==-- ";
echo " \n rubricForRestore->addPosition(); \n";
$positionId = $rubricForRestore->addPosition();
var_dump($positionId);
$position = new RubricPosition();
echo " \n position->id = $positionId => position->getStored(); \n";
$position->id = $positionId;
$position->getStored();
var_dump($position);
echo " \n position set : code name description isHidden ')\n";
$position->code = " position CODE $position->id ";
$position->name = " position NAME $position->id ";
$position->description = " position DESCRIPTION $position->id ";
$position->isHidden = InformationProperty::DEFINE_AS_NOT_HIDDEN;
var_dump($position);
echo " \n position->loadById($positionId); \n";
$position->loadById($positionId);
var_dump($position);
echo " \n position set : code name description isHidden ')\n";
$positionCode = " position CODE $position->id ";
$position->code = $positionCode;
$position->name = " position NAME $position->id ";
$position->description = " position DESCRIPTION $position->id ";
$position->isHidden = InformationProperty::DEFINE_AS_NOT_HIDDEN;
var_dump($position);
echo " \n position->mutateEntity(); \n";
$position->mutateEntity();
var_dump($position);
echo " \n testMutatePosition = new RubricPosition(); \n";
$testMutatePosition = new RubricPosition();
var_dump($testMutatePosition);
echo " \n testMutatePosition->loadByCode($positionCode); \n";
$testMutatePosition->loadByCode($positionCode);
var_dump($testMutatePosition);
echo " \n rubricForRestore->getMap(); \n";
$rubricMap = $rubricForRestore->getMap();
var_dump($rubricMap);
echo " \n --==@@ RUBRIC other POSITION @@==-- ";
echo " \n rubricForRestore->addPosition(); \n";
$otherPositionId = $rubricForRestore->addPosition();
var_dump($otherPositionId);
echo " \n otherPosition->loadById($otherPositionId); \n";
$otherPosition = new RubricPosition();
$otherPosition->loadById($otherPositionId);
var_dump($otherPosition);
echo " \n new otherPositionCode set : code name description isHidden ') \n";
$otherPositionId = $otherPosition->id;
$otherPositionCode = " otherPosition CODE $position->id ";
$otherPosition->code = $otherPositionCode;
$otherPosition->name = " otherPosition NAME $position->id ";
$otherPosition->description = " otherPosition DESCRIPTION $position->id ";
$otherPosition->isHidden = InformationProperty::DEFINE_AS_NOT_HIDDEN;
var_dump($otherPosition);
echo " \n otherPosition->mutateEntity(); \n";
$otherPosition->mutateEntity();
var_dump($otherPosition);
echo " \n rubricForRestore \n";
var_dump($rubricForRestore);
echo " \n rubricForRestore->getMap(); \n";
$rubricMap = $rubricForRestore->getMap();
var_dump($rubricMap);
// Задаём содержание свойству
echo " \n --==@@ set POSITION CONTENT @@==-- ";
echo " \n operatorPosition->id = $otherPositionId; operatorPosition->getStored() \n";
$operatorPosition = new RubricPosition();
$operatorPosition->id = $otherPositionId;
$operatorPosition->getStored();
var_dump($operatorPosition);
echo " \n operatorPosition->saveContent(' operatorPosition $operatorPosition->id ', $propertyCode); \n";
$isSuccess = $operatorPosition->saveContent(" operatorPosition $operatorPosition->id ", $propertyCode);
var_dump($isSuccess);
echo " \n positionCollection = operatorPosition->getPosition(); \n";
$positionCollection = $operatorPosition->getPosition();
var_dump($positionCollection);

// Добавим другую рубрику
echo " \n --==@@ other RUBRIC @@==-- ";
echo " \n rubric = new Rubric() \n";
$otherRubric = new Rubric();
var_dump($otherRubric);
echo " \n otherRubric->addEntity(); \n";
$result = $otherRubric->addEntity();
var_dump($otherRubric);
$rubricIdForRestore = $otherRubric->id;
echo " \n setup otherRubric code , name , desc for id => $otherRubric->id \n";
$otherRubric->code = " otherRubric CODE for $otherRubric->id";
$otherRubric->name = " otherRubric NAME for $otherRubric->id";
$otherRubric->description = " otherRubric DESCRIPTION for $otherRubric->id";
var_dump($otherRubric);
echo " \n otherRubric->mutateEntity(); \n";
$otherRubric->mutateEntity();
var_dump($otherRubric);
// Добавим два свойства
echo " \n --==@@ other INFORMATION PROPERTY @@==-- ";
echo " \n otherProperty = new InformationProperty(); \n";
$otherProperty = new InformationProperty();
var_dump($otherProperty);
echo " \n otherProperty->addEntity(); \n";
$isSuccess = $otherProperty->addEntity();
var_dump($otherProperty);
echo " \n otherProperty->setInformationDomain('SYSTEM_BETWEEN_INTEGER')\n";
$otherProperty->setInformationDomain('SYSTEM_BETWEEN_INTEGER');
var_dump($otherProperty);
echo " \n otherProperty set : code name description isHidden ')\n";
$otherPropertyId = $otherProperty->id;
$otherPropertyCode = " otherProperty CODE $otherPropertyId SYSTEM_BETWEEN_INTEGER ";
$otherProperty->code = $otherPropertyCode;
$otherProperty->name = " otherProperty NAME $otherPropertyId SYSTEM_BETWEEN_INTEGER ";
$otherProperty->description = " otherProperty DESCRIPTION $otherPropertyId SYSTEM_BETWEEN_INTEGER ";
$otherProperty->isHidden = InformationProperty::DEFINE_AS_NOT_HIDDEN;
var_dump($otherProperty);
echo " \n otherProperty->mutateEntity(); \n";
$isSuccess = $otherProperty->mutateEntity();
var_dump($otherProperty);
echo " \n --==@@ some other INFORMATION PROPERTY @@==-- ";
echo " \n someOtherProperty = new InformationProperty(); \n";
$someOtherProperty = new InformationProperty();
var_dump($someOtherProperty);
echo " \n someOtherProperty->addEntity(); \n";
$isSuccess = $someOtherProperty->addEntity();
var_dump($someOtherProperty);
echo " \n someOtherProperty->setInformationDomain('SYSTEM_ENUMERATION')\n";
$someOtherProperty->setInformationDomain('SYSTEM_ENUMERATION');
var_dump($someOtherProperty);
echo " \n someOtherProperty set : code name description isHidden ')\n";
$someOtherPropertyId = $someOtherProperty->id;
$someOtherPropertyCode = " someOtherProperty CODE $someOtherPropertyId SYSTEM_ENUMERATION ";
$someOtherProperty->code = $someOtherPropertyCode;
$someOtherProperty->name = " someOtherProperty NAME $someOtherPropertyId SYSTEM_ENUMERATION ";
$someOtherProperty->description = " someOtherProperty DESCRIPTION $someOtherPropertyId SYSTEM_ENUMERATION ";
$someOtherProperty->isHidden = InformationProperty::DEFINE_AS_NOT_HIDDEN;
var_dump($someOtherProperty);
echo " \n someOtherProperty->mutateEntity(); \n";
$isSuccess = $someOtherProperty->mutateEntity();
var_dump($someOtherProperty);
// добавили рубрике свойство
echo " \n --==@@ other POSITION INFORMATION PROPERTY @@==-- ";
echo " \n isSuccess = otherRubric->addProperty($otherPropertyCode); \n";
$isSuccess = $otherRubric->addProperty($otherPropertyCode);
var_dump($isSuccess);
echo " \n isSuccess = otherRubric->addProperty($someOtherPropertyCode); \n";
$isSuccess = $otherRubric->addProperty($someOtherPropertyCode);
var_dump($isSuccess);
echo " \n rubricForRestore->getProperties(); \n";
$rubricProperties = $otherRubric->getProperties();
var_dump($rubricProperties);
echo " \n --==@@ add other Position @@==-- ";
echo " \n otherRubricOnePositionId = otherRubric->addPosition(); ";
$otherRubricOnePositionId = $otherRubric->addPosition();
echo " \n otherRubricOtherPositionId = otherRubric->addPosition(); ";
$otherRubricOtherPositionId = $otherRubric->addPosition();

$otherRubricOnePosition = new RubricPosition();
$otherRubricOnePosition->id = $otherRubricOnePositionId;
$otherRubricOnePosition->getStored();
$otherRubricOnePosition->code = " Position $otherRubricOnePosition->id ";
$otherRubricOnePosition->mutateEntity();

$otherRubricOtherPosition = new RubricPosition();
$otherRubricOtherPosition->loadById($otherRubricOtherPositionId);
$otherRubricOtherPosition->code = " Position $otherRubricOtherPosition->id ";
$otherRubricOtherPosition->mutateEntity();

echo " \n otherRubricMap = otherRubric->getMap(); \n";
$otherRubricMap = $otherRubric->getMap();
var_dump($otherRubricMap);
// Задаём содержание свойствам
echo " \n --==@@ set CONTENT of some POSITION`s @@==-- ";
echo " \n otherRubric->saveContent x2 \n";



$isSuccess = $otherRubricOnePosition->saveContent(" first property otherRubricOnePosition $otherRubricOnePosition->id ",
    $otherPropertyCode);
$isSuccess = $otherRubricOnePosition->saveContent(" second property otherRubricOnePosition $otherRubricOnePosition->id ",
    $someOtherPropertyCode);

$isSuccess = $otherRubricOtherPosition->saveContent(" first property otherRubricOtherPosition $otherRubricOtherPosition->id ",
    $otherPropertyCode);
$isSuccess = $otherRubricOtherPosition->saveContent(" second property otherRubricOtherPosition $otherRubricOtherPosition->id ",
    $someOtherPropertyCode);

echo " \n onePositionProperty = otherRubricOnePosition->getPosition(); \n";
$onePositionProperty = $otherRubricOnePosition->getPosition();
var_dump($onePositionProperty);
echo " \n otherPositionProperty = otherRubricOtherPosition->getPosition(); \n";
$otherPositionProperty = $otherRubricOtherPosition->getPosition();
var_dump($otherPositionProperty);
echo " \n isSuccess = otherRubric->dropProperty(someOtherPropertyCode); \n";
$isSuccess = $otherRubric->dropProperty($someOtherPropertyCode);
var_dump($isSuccess);
echo " \n otherPositionProperty = otherRubricOtherPosition->getPosition(); \n";
$otherPositionProperty = $otherRubricOtherPosition->getPosition();
var_dump($otherPositionProperty);
echo '</pre>';



/*
echo '<pre>';

$Redactor = new Redactor();
var_export($Redactor);
$AdditionalValue = new AdditionalValue();
var_export($AdditionalValue);
$PropertyContent = new PropertyContent();
var_export($PropertyContent);
$PropertyContent = new PropertyContent();
var_export($PropertyContent);
$RubricPosition = new RubricPosition();
var_export($RubricPosition);
$RubricStructure = new RubricStructure();
var_export($RubricStructure);
$RubricInformationProperty = new RubricInformationProperty();
var_export($RubricInformationProperty);
$InformationPropertyDomain = new InformationPropertyDomain();
var_export($InformationPropertyDomain);
$InformationDomain = new InformationDomain();
var_export($InformationDomain);
$InformationProperty = new InformationProperty();
var_export($InformationProperty);

echo '</pre>';
*/

/*
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
*/
