<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.01.2017
 * Time: 13:37
 */

namespace Assay\DataAccess;

interface ISqlHandler
{
public function performQuery(array $arguments):array;
}
