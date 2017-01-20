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
