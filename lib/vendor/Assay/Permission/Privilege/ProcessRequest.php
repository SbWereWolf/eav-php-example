<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:10
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Common;
    use Assay\Core\Entity;
    use Assay\Core\IPrimitiveData;
    use Assay\Core\NamedEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    class ProcessRequest extends Common implements IProcessRequest
    {

        const OBJECT = 'object';
        const ACTION = 'action';
        const SESSION_ID = 'session_id';
        const IS_ALLOW = 'is_allow';
        /** @var string сессия */
        public $session;
        /** @var string процесс */
        public $process;
        /** @var string объект */
        public $object;
        /** @var string содержание процесса */
        public $content;

        public function checkPrivilege(array $args): array
        {
            $result[self::IS_ALLOW] = self::EMPTY_ARRAY;
            $object = self::isSetEx($args[self::OBJECT],self::EMPTY_VALUE);
            $action = self::isSetEx($args[self::ACTION],self::EMPTY_VALUE);
            $sessionId = self::isSetEx($args[self::SESSION_ID],self::EMPTY_VALUE);

            $process_field[ISqlHandler::PLACEHOLDER] = ':PROCESS';
            $process_field[ISqlHandler::VALUE] = $action;
            $process_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            $object_field[ISqlHandler::PLACEHOLDER] = ':OBJECT';
            $object_field[ISqlHandler::VALUE] = $object;
            $object_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            $sid_field[ISqlHandler::PLACEHOLDER] = ':SESSION_ID';
            $sid_field[ISqlHandler::VALUE] = $sessionId;
            $sid_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            $is_hidden_field[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden_field[ISqlHandler::VALUE] = IPrimitiveData::DEFINE_AS_NOT_HIDDEN;
            $is_hidden_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            $arguments[ISqlHandler::QUERY_TEXT] = "
                SELECT 
                    NULL 
                FROM 
                    ".Session::TABLE_NAME." S 
                JOIN 
                    ".Account::TABLE_NAME." U ON S.".Account::EXTERNAL_ID." = U.".Entity::ID."
                JOIN 
                    ".AccountRole::TABLE_NAME." UR ON U.".Entity::ID." = UR.".Account::EXTERNAL_ID."
                JOIN 
                    ".BusinessRole::TABLE_NAME." R ON R.".Entity::ID." = UR.".BusinessRole::EXTERNAL_ID."
                JOIN 
                    ".BusinessRolePrivilege::TABLE_NAME." RD ON RD.".BusinessRole::EXTERNAL_ID." = R.".Entity::ID." 
                JOIN 
                    ".BusinessObjectPrivilege::TABLE_NAME." P ON P.".Entity::ID." = RD.".BusinessObjectPrivilege::EXTERNAL_ID."
                JOIN 
                    ".BusinessProcess::TABLE_NAME." BP ON BP.".Entity::ID." = P.".BusinessProcess::EXTERNAL_ID."
                JOIN 
                    ".BusinessObject::TABLE_NAME." BO ON BO.".Entity::ID." = P.".BusinessObject::EXTERNAL_ID."
                WHERE
                    BP.".NamedEntity::CODE." = ".$process_field[ISqlHandler::PLACEHOLDER]." AND 
                    BO.".NamedEntity::CODE." = ".$object_field[ISqlHandler::PLACEHOLDER]." AND 
                    S.".Entity::ID." = ".$sid_field[ISqlHandler::PLACEHOLDER]." AND 
                    U.".IPrimitiveData::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND 
                    BO.".IPrimitiveData::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND 
                    BP.".IPrimitiveData::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND 
                    R.".IPrimitiveData::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND
                    S.".IPrimitiveData::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]."
            ";
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$process_field,$object_field,$sid_field,$is_hidden_field];
            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $isSuccessfulRead = SqlHandler::isNoError($response);
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $result[self::IS_ALLOW] = $record != Common::EMPTY_ARRAY;
            }

            return $result;
        }
    }
}
