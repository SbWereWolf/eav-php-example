<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19.01.2017
 * Time: 21:33
 */

namespace Assay\Core;


interface IConfiguration
{

    public function getDbCredentials():array;
}
