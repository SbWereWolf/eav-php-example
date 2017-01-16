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
    const QUERY_TEXT = 'QUERY_TEXT';
    const QUERY_PARAMETER = 'QUERY_PARAMETER';
    const QUERY_PLACEHOLDER = 'QUERY_PLACEHOLDER';
    const QUERY_VALUE = 'QUERY_VALUE';
    const QUERY_DATA_TYPE = 'QUERY_DATA_TYPE';
    
    public function performQuery(array $arguments):array;
}