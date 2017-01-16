<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.01.2017
 * Time: 14:47
 */

namespace Assay\Core;

class Configuration
{

    const DB_HOST = 'DB_HOST';
    const DB_PORT = 'DB_PORT';
    const DB_LOGIN = 'DB_LOGIN';
    const DB_PASSWORD = 'DB_PASSWORD';
    const DB_NAME = 'DB_NAME';
<<<<<<< HEAD
    const DB_TYPE = 'DB_TYPE';

    static protected $rInstance = null;
    protected $section = 'main';
=======
    const PDO_DBMS = 'PDO_DBMS';
>>>>>>> 77629b30ada6cc5b4efe11a7101e28f77435e118

    public $optionHost = 'DB_HOST';
    public $optionPort = 'DB_PORT';
    public $optionLogin = 'DB_LOGIN';
    public $optionPassword = 'DB_PASSWORD';
    public $optionName = 'DB_NAME';
<<<<<<< HEAD
    public $optionType = 'DB_TYPE';
    
    protected $rgIni;

    function __construct(string $path)
    {
        $this->rgIni = parse_ini_file($path, $this->section);
    }

    public function getCredentials()
    {
        return array(
            self::DB_HOST => $this->rgIni[$this->section][$this->optionHost],
            self::DB_PORT => $this->rgIni[$this->section][$this->optionPort],
            self::DB_LOGIN => $this->rgIni[$this->section][$this->optionLogin],
            self::DB_PASSWORD => $this->rgIni[$this->section][$this->optionPassword],
            self::DB_NAME => $this->rgIni[$this->section][$this->optionName]
        );
    }

    public function getWebpath()
    {
        $webRoot = preg_replace('#^http\:\/\/#i', '', $this->rgIni[$this->section]['web_root']);
        $webRoot = preg_replace('#^' . $_SERVER['HTTP_HOST'] . '#i', '', $webRoot);
        $webRoot = ltrim($webRoot, '/');
        return $webRoot;
    }

    public function getWebRoot()
    {
        return $this->rgIni[$this->section]['web_root'];
    }
=======
    public $optionDbms = 'PDO_DBMS';
    
    protected $settings;

    function __construct(string $path)
    {
        $this->settings = include($path);
    }

    public function getDbCredentials()
    {

        return array(
            self::DB_HOST => $this->settings[$this->optionHost],
            self::DB_PORT => $this->settings[$this->optionPort],
            self::DB_LOGIN => $this->settings[$this->optionLogin],
            self::DB_PASSWORD => $this->settings[$this->optionPassword],
            self::DB_NAME => $this->settings[$this->optionName],
            self::PDO_DBMS => $this->settings[$this->optionDbms],
        );
    }
>>>>>>> 77629b30ada6cc5b4efe11a7101e28f77435e118
}
