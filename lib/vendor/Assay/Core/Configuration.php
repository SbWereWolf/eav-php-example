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
    const PDO_DBMS = 'PDO_DBMS';

    public $optionHost = 'DB_HOST';
    public $optionPort = 'DB_PORT';
    public $optionLogin = 'DB_LOGIN';
    public $optionPassword = 'DB_PASSWORD';
    public $optionName = 'DB_NAME';
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
}
