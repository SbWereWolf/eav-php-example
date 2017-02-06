<?php

use Assay\InformationsCatalog\DataInformation\DigitalContent;
use Assay\InformationsCatalog\DataInformation\RubricPosition;
use Assay\InformationsCatalog\DataInformation\StringContent;
use Assay\InformationsCatalog\RedactorContent\DigitalValue;
use Assay\InformationsCatalog\RedactorContent\StringValue;
use Assay\InformationsCatalog\StructureInformation\InformationProperty;
use Assay\InformationsCatalog\StructureInformation\Rubric;

include "autoloader.php";


echo '<pre>';


$kmCode = 'TRANSPORTATION_PRICE_KM';
$tonCode = 'TRANSPORTATION_PRICE_TONN';
$goodsPriceCode = 'GOODS_PRICE';
$goodsUnitsOfMeasureCode = 'GOODS_UNITS_OF_MEASURE';
$sysLike = 'CODE 34';
$sysStringEnum = 'CODE 35';
$sysDigitEnum = 'CODE 36';
$companyBetweenDigital = 'CODE 37';
$companyDigitEnum = 'CODE 38';
$userLike = 'CODE 39';
$sysBetweenDigital = 'CODE 40';

$rubric = new Rubric();
$rubric->loadById(181);
$rubricSearchParameters = $rubric->getSearchParameters();


echo " \n --==@@ SEARCH rubricSearchParameters @@==-- \n ";
$rubricSearchParametersJson = json_encode($rubricSearchParameters);
var_dump($rubricSearchParametersJson);

$codeForStringSearch = $sysLike ;
$filterProperties['description'][$codeForStringSearch]=$rubricSearchParameters['description'][$codeForStringSearch];
$filterProperties['description'][$codeForStringSearch]['description'][]='nothing';


$codeForDigitalSearch = $sysDigitEnum;
$filterProperties['description'][$codeForDigitalSearch]=$rubricSearchParameters['description'][$codeForDigitalSearch];


$searchResult = $rubric->search($filterProperties);
echo " \n --==@@ SEARCH filterProperties @@==-- \n ";
$filterPropertiesJson = json_encode($filterProperties);
var_dump($filterPropertiesJson);
echo " \n --==@@ SEARCH searchResult @@==-- \n ";
$searchResultJson = json_encode($searchResult);
var_dump($searchResultJson);


echo '</pre>';


/*
echo '<pre>';

echo " \n --==@@ RubricPosition COD testing @@==-- \n ";
$rubric = new Rubric();
echo " \n rubric->addEntity(); \n ";
$rubric->addEntity();
var_dump($rubric);


echo '</pre>';
*/

/*
echo '<pre>';

echo " \n --==@@ RubricPosition COD testing @@==-- \n ";
$rubric = new Rubric();
//echo " \n rubric->addEntity(); \n ";
$rubric->addEntity();
//var_dump($rubric);
$sysBetweenDigital = 'CODE 40';
$rubric->addProperty($sysBetweenDigital);
//echo " \n rubricPositionId = rubric->addPosition(); \n ";
$rubricPositionId = $rubric->addPosition();
//var_dump($rubricPositionId);
$rubricPosition = new RubricPosition();
//echo " \n rubricPosition->loadById($rubricPositionId); \n ";
$rubricPosition->loadById($rubricPositionId);
//var_dump($rubricPosition);
$rubricPosition->code = 'next'.time();
//echo " \n before rubricPosition->mutateEntity(); \n ";
//var_dump($rubricPosition);
echo " \n isSuccess = rubricPosition->mutateEntity(); \n ";
$isSuccess = $rubricPosition->mutateEntity();

echo '</pre>';
*/

/*
echo '<pre>';

$kmCode = 'TRANSPORTATION_PRICE_KM';
$tonCode = 'TRANSPORTATION_PRICE_TONN';
$goodsPriceCode = 'GOODS_PRICE';
$goodsUnitsOfMeasureCode = 'GOODS_UNITS_OF_MEASURE';
$sysLike = 'CODE 34';
$sysStringEnum = 'CODE 35';
$sysDigitEnum = 'CODE 36';
$companyBetweenDigital = 'CODE 37';
$companyDigitEnum = 'CODE 38';
$userLike = 'CODE 39';
$sysBetweenDigital = 'CODE 40';

$rubric_code = 'save content test 201702041943';

$rubric = new Rubric();

echo " \n rubric->loadByCode($rubric_code); \n ";
$rubric->loadByCode($rubric_code);
var_dump($rubric);

echo " \n rubricPositionId = rubric->addPosition(); \n ";
$rubricPositionId = $rubric->addPosition();
var_dump($rubricPositionId );

$rubricPosition = new RubricPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->code = 'next';
$isSuccess = $rubricPosition->mutateEntity();
var_dump($isSuccess);
var_dump($rubricPosition);
$rubricPosition->saveContent(' user string 34', $sysLike);
$rubricPosition->saveContent(' sys enum4', $sysStringEnum);
$rubricPosition->saveContent(' user enum3', $userLike);
$rubricPosition->saveContent('5', $sysDigitEnum);
$rubricPosition->saveContent('1', $companyBetweenDigital);
$rubricPosition->saveContent('15.1', $companyDigitEnum);
$rubricPosition->saveContent('9.99', $sysBetweenDigital);
$rubricPosition->saveContent('150', $kmCode);
$rubricPosition->saveContent('1', $tonCode);
$rubricPosition->saveContent('15.159', $goodsPriceCode);
$rubricPosition->saveContent('ty', $goodsUnitsOfMeasureCode);


echo '</pre>';
*/

/*
echo '<pre>';

$kmCode = 'TRANSPORTATION_PRICE_KM';
$tonCode = 'TRANSPORTATION_PRICE_TONN';
$goodsPriceCode = 'GOODS_PRICE';
$goodsUnitsOfMeasureCode = 'GOODS_UNITS_OF_MEASURE';
$sysLike = 'CODE 34';
$sysStringEnum = 'CODE 35';
$sysDigitEnum = 'CODE 36';
$companyBetweenDigital = 'CODE 37';
$companyDigitEnum = 'CODE 38';
$userLike = 'CODE 39';
$sysBetweenDigital = 'CODE 40';

$rubric_code = 'save content test 201702041943';

$rubric = new Rubric();
//$rubric->addEntity();

//$rubric->code = $rubric_code;
//$rubric->name = 'контент';
//$rubric->description= 'тестирование функционала';
//$rubric->mutateEntity();

echo " \n rubric->loadByCode($rubric_code); \n ";
$rubric->loadByCode($rubric_code);
var_dump($rubric);

echo " \n rubricPositionId = rubric->addPosition(); \n ";
$rubricPositionId = $rubric->addPosition();
var_dump($rubricPositionId );
$rubricPosition = new RubricPosition();

echo " \n rubricPosition->loadById($rubricPositionId) \n ";
$rubricPosition->loadById($rubricPositionId);
var_dump($rubricPosition);
echo " \n rubricPosition->mutateEntity(); \n ";
$rubricPosition->code = 'первая позиция';
$isSuccess = $rubricPosition->mutateEntity();
var_dump($isSuccess);
var_dump($rubricPosition);

$rubricPosition->saveContent(' user some string', $sysLike);
$rubricPosition->saveContent(' sys enum1', $sysStringEnum);
$rubricPosition->saveContent(' user enum1', $userLike);
$rubricPosition->saveContent('15', $sysDigitEnum);
$rubricPosition->saveContent('18', $companyBetweenDigital);
$rubricPosition->saveContent('15.15', $companyDigitEnum);
$rubricPosition->saveContent('99.99', $sysBetweenDigital);
$rubricPosition->saveContent('15', $kmCode);
$rubricPosition->saveContent('18', $tonCode);
$rubricPosition->saveContent('15.15', $goodsPriceCode);
$rubricPosition->saveContent('tt', $goodsUnitsOfMeasureCode);

echo " \n rubricPositionId = rubric->addPosition(); \n ";
$rubricPositionId = $rubric->addPosition();
var_dump($rubricPositionId );
$rubricPosition = new RubricPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->code = 'следующая';
$isSuccess = $rubricPosition->mutateEntity();
var_dump($isSuccess);
var_dump($rubricPosition);
$rubricPosition->saveContent(' user same string', $sysLike);
$rubricPosition->saveContent(' sys enum1', $sysStringEnum);
$rubricPosition->saveContent(' user enum1', $userLike);
$rubricPosition->saveContent('15', $sysDigitEnum);
$rubricPosition->saveContent('18', $companyBetweenDigital);
$rubricPosition->saveContent('15.15', $companyDigitEnum);
$rubricPosition->saveContent('99.99', $sysBetweenDigital);
$rubricPosition->saveContent('15', $kmCode);
$rubricPosition->saveContent('18', $tonCode);
$rubricPosition->saveContent('15.15', $goodsPriceCode);
$rubricPosition->saveContent('tt', $goodsUnitsOfMeasureCode);

echo " \n rubricPositionId = rubric->addPosition(); \n ";
$rubricPositionId = $rubric->addPosition();
var_dump($rubricPositionId );
$rubricPosition = new RubricPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->code = 'third';
$isSuccess = $rubricPosition->mutateEntity();
var_dump($isSuccess);
var_dump($rubricPosition);
$rubricPosition->saveContent(' user other string', $sysLike);
$rubricPosition->saveContent(' sys enum1', $sysStringEnum);
$rubricPosition->saveContent(' user enum1', $userLike);
$rubricPosition->saveContent('15', $sysDigitEnum);
$rubricPosition->saveContent('18', $companyBetweenDigital);
$rubricPosition->saveContent('15.15', $companyDigitEnum);
$rubricPosition->saveContent('99.99', $sysBetweenDigital);
$rubricPosition->saveContent('15', $kmCode);
$rubricPosition->saveContent('18', $tonCode);
$rubricPosition->saveContent('15.15', $goodsPriceCode);
$rubricPosition->saveContent('tt', $goodsUnitsOfMeasureCode);

echo " \n rubricPositionId = rubric->addPosition(); \n ";
$rubricPositionId = $rubric->addPosition();
var_dump($rubricPositionId );
$rubricPosition = new RubricPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->code = 'next';
$isSuccess = $rubricPosition->mutateEntity();
var_dump($isSuccess);
var_dump($rubricPosition);
$rubricPosition->saveContent(' user string 34', $sysLike);
$rubricPosition->saveContent(' sys enum4', $sysStringEnum);
$rubricPosition->saveContent(' user enum3', $userLike);
$rubricPosition->saveContent('5', $sysDigitEnum);
$rubricPosition->saveContent('1', $companyBetweenDigital);
$rubricPosition->saveContent('15.1', $companyDigitEnum);
$rubricPosition->saveContent('9.99', $sysBetweenDigital);
$rubricPosition->saveContent('150', $kmCode);
$rubricPosition->saveContent('1', $tonCode);
$rubricPosition->saveContent('15.159', $goodsPriceCode);
$rubricPosition->saveContent('ty', $goodsUnitsOfMeasureCode);

echo " \n rubricPositionId = rubric->addPosition(); \n ";
$rubricPositionId = $rubric->addPosition();
var_dump($rubricPositionId );
$rubricPosition = new RubricPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->code = 'continue';
$isSuccess = $rubricPosition->mutateEntity();
var_dump($isSuccess);
var_dump($rubricPosition);
$rubricPosition->saveContent('string', $sysLike);
$rubricPosition->saveContent(' sys enum1', $sysStringEnum);
$rubricPosition->saveContent(' user enum3', $userLike);
$rubricPosition->saveContent('15,0', $sysDigitEnum);
$rubricPosition->saveContent('18,o', $companyBetweenDigital);
$rubricPosition->saveContent('15,15', $companyDigitEnum);
$rubricPosition->saveContent('100', $sysBetweenDigital);
$rubricPosition->saveContent('5', $kmCode);
$rubricPosition->saveContent('1', $tonCode);
$rubricPosition->saveContent('150.15', $goodsPriceCode);
$rubricPosition->saveContent('yy', $goodsUnitsOfMeasureCode);

echo " \n rubricPositionId = rubric->addPosition(); \n ";
$rubricPositionId = $rubric->addPosition();
var_dump($rubricPositionId );
$rubricPosition = new RubricPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->code = 'last последняя';
$isSuccess = $rubricPosition->mutateEntity();
var_dump($isSuccess);
var_dump($rubricPosition);
$rubricPosition->saveContent('nothing', $sysLike);
$rubricPosition->saveContent(' sys enum1', $sysStringEnum);
$rubricPosition->saveContent(' user enum1', $userLike);
$rubricPosition->saveContent('13', $sysDigitEnum);
$rubricPosition->saveContent('19', $companyBetweenDigital);
$rubricPosition->saveContent('13.15', $companyDigitEnum);
$rubricPosition->saveContent('51.99', $sysBetweenDigital);
$rubricPosition->saveContent('16', $kmCode);
$rubricPosition->saveContent('21', $tonCode);
$rubricPosition->saveContent('75.15', $goodsPriceCode);
$rubricPosition->saveContent('ty', $goodsUnitsOfMeasureCode);


echo '</pre>';

*/

/*
echo '<pre>';

$kmCode = 'TRANSPORTATION_PRICE_KM';
$tonCode = 'TRANSPORTATION_PRICE_TONN';
$goodsPriceCode = 'GOODS_PRICE';
$goodsUnitsOfMeasureCode = 'GOODS_UNITS_OF_MEASURE';
$sysLike = 'CODE 34';
$sysStringEnum = 'CODE 35';
$sysDigitEnum = 'CODE 36';
$companyBetweenDigital = 'CODE 37';
$companyDigitEnum = 'CODE 38';
$userLike = 'CODE 39';
$sysBetweenDigital = 'CODE 40';

$rubric_code = 'save content test 201702041943';

$rubric = new Rubric();
echo " \n stringValue = new StringValue(); \n ";
$rubric->loadByCode($rubric_code);
$rubric->addProperty($kmCode);
$rubric->addProperty($tonCode);
$rubric->addProperty($goodsPriceCode);
$rubric->addProperty($goodsUnitsOfMeasureCode);
$rubric->addProperty($sysLike);
$rubric->addProperty($sysStringEnum);
$rubric->addProperty($sysDigitEnum);
$rubric->addProperty($companyBetweenDigital);
$rubric->addProperty($companyDigitEnum);
$rubric->addProperty($userLike);
$rubric->addProperty($sysBetweenDigital);
$rubricProperties = $rubric->getProperties();
var_dump($rubricProperties);

echo '</pre>';
*/

/*
echo '<pre>';

$kmCode = 'TRANSPORTATION_PRICE_KM';
$tonCode = 'TRANSPORTATION_PRICE_TONN';
$goodsPriceCode = 'GOODS_PRICE';
$goodsUnitsOfMeasureCode = 'GOODS_UNITS_OF_MEASURE';
$sysLike = 'CODE 34';
$sysStringEnum = 'CODE 35';
$sysDigitEnum = 'CODE 36';
$companyBetweenDigital = 'CODE 37';
$companyDigitEnum = 'CODE 38';
$userLike = 'CODE 39';
$sysBetweenDigital = 'CODE 40';

$informationProperty = new InformationProperty();

echo " \n informationProperty->loadByCode($kmCode) \n ";
$isSuccess = $informationProperty->loadByCode($kmCode);
var_dump($informationProperty);
echo " \n informationProperty->setInformationDomain('USER_BETWEEN_DIGITAL') \n ";
$isSuccess = $informationProperty->setInformationDomain('USER_BETWEEN_DIGITAL');
var_dump($informationProperty);
echo " \n informationProperty->mutateEntity(); \n ";
$isSuccess = $informationProperty->mutateEntity();
var_dump($isSuccess);
echo " \n informationProperty->loadByCode($tonCode); \n ";
$isSuccess = $informationProperty->loadByCode($tonCode);
var_dump($informationProperty);
echo " \n informationProperty->setInformationDomain('USER_BETWEEN_DIGITAL'); \n ";
$isSuccess = $informationProperty->setInformationDomain('USER_BETWEEN_DIGITAL');
var_dump($informationProperty);
echo " \n informationProperty->mutateEntity(); \n ";
$isSuccess = $informationProperty->mutateEntity();
var_dump($isSuccess);
echo " \n informationProperty->loadByCode($goodsPriceCode); \n ";
$isSuccess = $informationProperty->loadByCode($goodsPriceCode);
var_dump($informationProperty);
echo " \n informationProperty->setInformationDomain('USER_BETWEEN_DIGITAL'); \n ";
$isSuccess = $informationProperty->setInformationDomain('USER_BETWEEN_DIGITAL');
var_dump($informationProperty);
echo " \n informationProperty->mutateEntity(); \n ";
$isSuccess = $informationProperty->mutateEntity();
var_dump($isSuccess);
echo " \n informationProperty->loadByCode($goodsUnitsOfMeasureCode); \n ";
$isSuccess = $informationProperty->loadByCode($goodsUnitsOfMeasureCode);
var_dump($informationProperty);
echo " \n informationProperty->setInformationDomain('USER_STRING_ENUMERATION'); \n ";
$isSuccess = $informationProperty->setInformationDomain('USER_STRING_ENUMERATION');
var_dump($informationProperty);

echo '</pre>';
*/

/*
$isSuccess = $informationProperty->loadByCode($sysLike);
$isSuccess = $informationProperty->setInformationDomain('SYSTEM_LIKE');
$isSuccess = $informationProperty->mutateEntity();
$isSuccess = $informationProperty->loadByCode($sysStringEnum);
$isSuccess = $informationProperty->setInformationDomain('SYSTEM_STRING_ENUMERATION');
$isSuccess = $informationProperty->mutateEntity();
$isSuccess = $informationProperty->loadByCode($sysDigitEnum);
$isSuccess = $informationProperty->setInformationDomain('SYSTEM_DIGITAL_ENUMERATION');
$isSuccess = $informationProperty->mutateEntity();
$isSuccess = $informationProperty->loadByCode($companyBetweenDigital);
$isSuccess = $informationProperty->setInformationDomain('COMPANY_BETWEEN_DIGITAL');
$isSuccess = $informationProperty->mutateEntity();
$isSuccess = $informationProperty->loadByCode($companyDigitEnum);
$isSuccess = $informationProperty->setInformationDomain('COMPANY_DIGITAL_ENUMERATION');
$isSuccess = $informationProperty->mutateEntity();
$isSuccess = $informationProperty->loadByCode($userLike);
$isSuccess = $informationProperty->setInformationDomain('USER_LIKE');
$isSuccess = $informationProperty->mutateEntity();
$isSuccess = $informationProperty->loadByCode($sysBetweenDigital);
$isSuccess = $informationProperty->setInformationDomain('SYSTEM_BETWEEN_DIGITAL');
$isSuccess = $informationProperty->mutateEntity();
*/

/*
echo '<pre>';


echo " \n stringValue = new StringValue(); \n ";
$stringValue = new StringValue();
var_dump($stringValue );
echo " \n digitalValue = new DigitalValue(); \n ";
$digitalValue = new DigitalValue();
var_dump($digitalValue );
echo " \n stringContent = new StringContent(); \n ";
$stringContent = new StringContent();
var_dump($stringContent );
echo " \n digitalContent = new DigitalContent(); \n ";
$digitalContent = new DigitalContent();
var_dump($digitalContent );


echo '</pre>';
*/

/*
echo '<pre>';


$kmCode = 'TRANSPORTATION_PRICE_KM';
$tonCode = 'TRANSPORTATION_PRICE_TONN';
$goodsPriceCode = 'GOODS_PRICE';
$goodsUnitsOfMeasureCode = 'GOODS_UNITS_OF_MEASURE';
$codeUserLike = 'CODE 34';
$codeSysEnum = 'CODE 35';
$codeUserEnum = 'CODE 36';
$codeSysBetweenInt = 'CODE 37';
$codeUserBetweenInt = 'CODE 38';
$codeSysBetweenFloat = 'CODE 39';
$codeUserBetweenFloat = 'CODE 40';

$rubric = new Rubric();
$rubric->loadById(178);
$rubricSearchParameters = $rubric->getSearchParameters();
var_dump($rubricSearchParameters);
$filterProperties['description'][$codeUserLike]=$rubricSearchParameters['description'][trim($codeUserLike)];
$filterProperties['description'][$codeUserLike]['description'][]='user string';
$searchResult = $rubric->search($filterProperties, 2, 2);


echo '</pre>';
*/

/*
echo '<pre>';


echo " \n --==@@ SEARCH RUBRIC @@==-- \n ";

$kmCode = 'TRANSPORTATION_PRICE_KM';
$tonCode = 'TRANSPORTATION_PRICE_TONN';
$goodsPriceCode = 'GOODS_PRICE';
$goodsUnitsOfMeasureCode = 'GOODS_UNITS_OF_MEASURE';
$codeUserLike = 'CODE 34';
$codeSysEnum = 'CODE 35';
$codeUserEnum = 'CODE 36';
$codeSysBetweenInt = 'CODE 37';
$codeUserBetweenInt = 'CODE 38';
$codeSysBetweenFloat = 'CODE 39';
$codeUserBetweenFloat = 'CODE 40';

$rubric = new Rubric();
$rubric->addEntity();
$rubricPositionId = $positionId = $rubric->addPosition();
$rubricPosition = new RubricPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->saveContent(' user string', $codeUserLike);
$rubricPosition->saveContent(' sys enum1', $codeSysEnum);
$rubricPosition->saveContent(' user enum1', $codeUserEnum);
$rubricPosition->saveContent('15', $codeSysBetweenInt);
$rubricPosition->saveContent('18', $codeUserBetweenInt);
$rubricPosition->saveContent('15.15', $codeSysBetweenFloat);
$rubricPosition->saveContent('99.99', $codeUserBetweenFloat);

$rubricPositionId = $positionId = $rubric->addPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->saveContent(' string user', $codeUserLike);
$rubricPosition->saveContent(' sys enum2', $codeSysEnum);
$rubricPosition->saveContent(' user enum2', $codeUserEnum);
$rubricPosition->saveContent('13', $codeSysBetweenInt);
$rubricPosition->saveContent('19', $codeUserBetweenInt);
$rubricPosition->saveContent('12.15', $codeSysBetweenFloat);
$rubricPosition->saveContent('100.99', $codeUserBetweenFloat);

$rubricPositionId = $positionId = $rubric->addPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->saveContent(' user string user', $codeUserLike);
$rubricPosition->saveContent(' sys enum1', $codeSysEnum);
$rubricPosition->saveContent(' user enum1', $codeUserEnum);
$rubricPosition->saveContent('154', $codeSysBetweenInt);
$rubricPosition->saveContent('1', $codeUserBetweenInt);
$rubricPosition->saveContent('145.15', $codeSysBetweenFloat);
$rubricPosition->saveContent('9.99', $codeUserBetweenFloat);

$rubricPositionId = $positionId = $rubric->addPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->saveContent(' user string', $codeUserLike);
$rubricPosition->saveContent(' sys enum2', $codeSysEnum);
$rubricPosition->saveContent(' user enum2', $codeUserEnum);
$rubricPosition->saveContent('5', $codeSysBetweenInt);
$rubricPosition->saveContent('8', $codeUserBetweenInt);
$rubricPosition->saveContent('5.15', $codeSysBetweenFloat);
$rubricPosition->saveContent('1099.99', $codeUserBetweenFloat);

$rubricPositionId = $positionId = $rubric->addPosition();
$rubricPosition->loadById($rubricPositionId);
$rubricPosition->saveContent('xxxx', $codeUserLike);
$rubricPosition->saveContent(' sys enum3', $codeSysEnum);
$rubricPosition->saveContent(' user enum3', $codeUserEnum);
$rubricPosition->saveContent('0', $codeSysBetweenInt);
$rubricPosition->saveContent('-5', $codeUserBetweenInt);
$rubricPosition->saveContent('-5.15', $codeSysBetweenFloat);
$rubricPosition->saveContent('+1099.99', $codeUserBetweenFloat);


echo '</pre>';
*/

/*
echo '<pre>';


$informationProperty = new InformationProperty();

$informationProperty->loadByCode($code34);
$informationProperty->setInformationDomain('USER_LIKE');
$informationProperty->mutateEntity();
$informationProperty->loadByCode($code35);
$informationProperty->setInformationDomain('SYSTEM_ENUMERATION');
$informationProperty->mutateEntity();
$informationProperty->loadByCode($code36);
$informationProperty->setInformationDomain('USER_ENUMERATION');
$informationProperty->mutateEntity();
$informationProperty->loadByCode($code37);
$informationProperty->setInformationDomain('SYSTEM_BETWEEN_INTEGER');
$informationProperty->mutateEntity();
$informationProperty->loadByCode($code38);
$informationProperty->setInformationDomain('USER_BETWEEN_INTEGER');
$informationProperty->mutateEntity();
$informationProperty->loadByCode($code39);
$informationProperty->setInformationDomain('SYSTEM_BETWEEN_FLOAT');
$informationProperty->mutateEntity();
$informationProperty->loadByCode($code40);
$informationProperty->setInformationDomain('USER_BETWEEN_FLOAT');
$informationProperty->mutateEntity();

$rubric = new Rubric();
$rubric->addEntity();
$rubric->name = 'test get rubric search ';
$rubric->mutateEntity();
$rubric->addProperty($kmCode);
$rubric->addProperty($tonCode);
$rubric->addProperty($goodsPriceCode);
$rubric->addProperty($goodsUnitsOfMeasureCode);
$rubric->addProperty($code34);
$rubric->addProperty($code35);
$rubric->addProperty($code36);
$rubric->addProperty($code37);
$rubric->addProperty($code38);
$rubric->addProperty($code39);
$rubric->addProperty($code40);
$rubricProperties = $rubric->getProperties();
var_dump($rubricProperties);

echo " \n --==@@ RUBRIC @@==-- ";
$kmCode = 'TRANSPORTATION_PRICE_KM';
$tonCode = 'TRANSPORTATION_PRICE_TONN';
$code40 = ' CODE 40';
$goodsPriceCode = 'GOODS_PRICE';
$goodsUnitsOfMeasureCode = 'GOODS_UNITS_OF_MEASURE';

$rubric = new Rubric();
$rubric->addEntity();
$rubric->name = 'test get shipping position';
$rubric->mutateEntity();
var_dump($rubric);
$rubric->addProperty($kmCode);
$rubric->addProperty($tonCode);
$rubric->addProperty($code40);
$rubric->addProperty($goodsPriceCode);
$rubric->addProperty($goodsUnitsOfMeasureCode);

$positionId = $rubric->addPosition();
$rubricPosition = new RubricPosition();
$rubricPosition->loadById($positionId);
$rubricPosition->saveContent(' some string', $code40);
echo " \n positionContent = rubricPosition->getPositionContent(); \n";
$positionContent = $rubricPosition->getPositionContent();
var_dump($positionContent);

$redactorIdVasya = 3;
$rubricPosition->saveValue('3.1415926', $kmCode, $redactorIdVasya);
$rubricPosition->saveValue('3,1415926', $tonCode, $redactorIdVasya);
$rubricPosition->saveValue(' OTHER STR ', $code40, $redactorIdVasya);
$rubricPosition->saveValue('2.99', $goodsPriceCode, $redactorIdVasya);
$rubricPosition->saveValue('шт.', $goodsUnitsOfMeasureCode, $redactorIdVasya);

echo " \n rubricPosition->getPositionValue(); \n";
$positionValue = $rubricPosition->getPositionValue();
var_dump($positionValue);
echo " \n rubricPosition->getShippingPricing(); \n";
$shippingPricing = $rubricPosition->getShippingPricing();
var_dump($shippingPricing);
echo " \n goodsPricing = rubricPosition->getGoodsPricing(); \n";
$goodsPricing = $rubricPosition->getGoodsPricing();
var_dump($goodsPricing);


echo '</pre>';
*/


/*

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
$positionCollection = $operatorPosition->getPositionContent();
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
$onePositionProperty = $otherRubricOnePosition->getPositionContent();
var_dump($onePositionProperty);
echo " \n otherPositionProperty = otherRubricOtherPosition->getPosition(); \n";
$otherPositionProperty = $otherRubricOtherPosition->getPositionContent();
var_dump($otherPositionProperty);
echo " \n isSuccess = otherRubric->dropProperty(someOtherPropertyCode); \n";
$isSuccess = $otherRubric->dropProperty($someOtherPropertyCode);
var_dump($isSuccess);
echo " \n otherPositionProperty = otherRubricOtherPosition->getPosition(); \n";
$otherPositionProperty = $otherRubricOtherPosition->getPositionContent();
var_dump($otherPositionProperty);
echo " \n --==@@ REDACTOR @@==-- ";
$redactor = new Redactor();
var_export($redactor);
echo " \n redactor->addEntity(); \n";
$redactor->addEntity();
var_export($redactor);
$vasyaId = $redactor->id;
echo " \n redactor->name = vasya merzlyakov \n";
$redactor->name = 'vasya merzlyakov';
var_export($redactor);
echo " \n redactor->mutateEntity(); \n";
$redactor->mutateEntity();
var_export($redactor);
$vasyaRedactor = new Redactor();
echo " \n vasyaRedactor->getStored(); \n";
$vasyaRedactor->id = $vasyaId;
$vasyaRedactor->getStored();
var_export($redactor);
echo " \n otherRubricOnePosition->saveValue(' vasino znachenie ', $otherPropertyCode, $vasyaRedactor->id); \n";
$vasyaValue = $otherRubricOnePosition->saveValue(' vasino znachenie ', $otherPropertyCode, $vasyaRedactor->id);
var_export($vasyaValue);
echo " \n onePositionValue = otherRubricOnePosition->getPositionValue(); \n";
$onePositionValue = $otherRubricOnePosition->getPositionValue();
var_export($onePositionValue );
echo " \n vasyaOtherValue = otherRubricOtherPosition->saveValue(' zna4enie dlya other vasino ', 
$otherPropertyCode, $vasyaRedactor->id); \n";
$vasyaOtherValue = $otherRubricOtherPosition->saveValue(' zna4enie dlya other vasino ',
    $otherPropertyCode, $vasyaRedactor->id);
var_export($vasyaOtherValue);
echo " \n otherPositionValue = otherRubricOtherPosition->getPositionValue(); \n";
$otherPositionValue = $otherRubricOtherPosition->getPositionValue();
var_export($otherPositionValue);
$petyaRedactor = new Redactor();
$petyaRedactor->addEntity();
$petyaId = $petyaRedactor->id;
$petyaRedactor->name=' petya ';
echo " \n petyaRedactor->mutateEntity(); \n";
$petyaRedactor->mutateEntity();
var_export($petyaRedactor);
echo " \n petyaOtherValue = otherRubricOtherPosition->saveValue(' zna4enie dlya other vasino ',
    $otherPropertyCode, $petyaRedactor->id); \n";
$petyaOtherValue = $otherRubricOtherPosition->saveValue(' petino zna4enie ',
    $otherPropertyCode, $petyaRedactor->id);
var_export($petyaOtherValue);
echo " \n otherPositionValue = otherRubricOtherPosition->getPositionValue(); \n";
$otherPositionValue = $otherRubricOtherPosition->getPositionValue();
var_export($otherPositionValue);

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
