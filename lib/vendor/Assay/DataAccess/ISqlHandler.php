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

    const EXEC_WITH_SUCCESS_CODE = '00000';
    const EXEC_WITH_SUCCESS_MESSAGE = null;
    const EXEC_WITH_SUCCESS_NUMBER = null;
    
    const EXEC_ERROR_CODE_INDEX = 0;
    const EXEC_ERROR_NUMBER_INDEX = 1;
    const EXEC_ERROR_MESSAGE_INDEX = 2;
    
    public function performQuery(array $arguments):array;
    public static function getFirstRecord(array $response):array;
    public static function getAllRecords(array $response):array;
}
