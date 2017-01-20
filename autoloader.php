<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 20.01.17
 * Time: 17:39
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('CONFIGURATION_ROOT', realpath(__DIR__ . DIRECTORY_SEPARATOR . 'configuration'));
define('DB_READ_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_read.php');
define('DB_WRITE_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_write.php');

function autoload($className)
{
    $path = __DIR__ . "/lib/vendor/";
    $path = str_replace('\/',DIRECTORY_SEPARATOR,$path);
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