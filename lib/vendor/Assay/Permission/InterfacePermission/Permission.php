<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 20.01.17
 * Time: 16:56
 */
namespace Assay\Permission\InterfacePermission {

    use Assay\Core\Common;
    use Assay\Core\ICommon;
    use Assay\Core\IEntity;
    use Assay\Core\NamedEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;
    use Assay\Permission\Privilege\BusinessObject;
    use Assay\Permission\Privilege\BusinessProcess;
    use Assay\Permission\Privilege\BusinessRole;
    use Assay\Permission\Privilege\ObjectPrivilege;
    use Assay\Permission\Privilege\RoleDetail;
    use Assay\Permission\Privilege\Session;
    use Assay\Permission\Privilege\User;
    use Assay\Permission\Privilege\UserRole;

    Class Permission implements IPermission {
        public function checkPrivilege(array $args): array
        {
            $result[self::IS_ALLOW] = ICommon::EMPTY_ARRAY;
            $object = Common::isSetEx($args[self::OBJECT],ICommon::EMPTY_VALUE);
            $action = Common::isSetEx($args[self::ACTION],ICommon::EMPTY_VALUE);
            $sessionId = Common::isSetEx($args[self::SESSION_ID],ICommon::EMPTY_VALUE);

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
            $is_hidden_field[ISqlHandler::VALUE] = IEntity::DEFINE_AS_NOT_HIDDEN;
            $is_hidden_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            $arguments[ISqlHandler::QUERY_TEXT] = "
                SELECT 
                    NULL 
                FROM 
                    ".Session::TABLE_NAME." S 
                JOIN 
                    ".User::TABLE_NAME." U ON S.".User::EXTERNAL_ID." = U.".IEntity::ID."
                JOIN 
                    ".UserRole::TABLE_NAME." UR ON U.".IEntity::ID." = UR.".User::EXTERNAL_ID."
                JOIN 
                    ".BusinessRole::TABLE_NAME." R ON R.".IEntity::ID." = UR.".BusinessRole::EXTERNAL_ID."
                JOIN 
                    ".RoleDetail::TABLE_NAME." RD ON RD.".BusinessRole::EXTERNAL_ID." = R.".IEntity::ID." 
                JOIN 
                    ".ObjectPrivilege::TABLE_NAME." P ON P.".IEntity::ID." = RD.".ObjectPrivilege::EXTERNAL_ID."
                JOIN 
                    ".BusinessProcess::TABLE_NAME." BP ON BP.".IEntity::ID." = P.".BusinessProcess::EXTERNAL_ID."
                JOIN 
                    ".BusinessObject::TABLE_NAME." BO ON BO.".IEntity::ID." = P.".BusinessObject::EXTERNAL_ID."
                WHERE
                    BP.".NamedEntity::CODE." = ".$process_field[ISqlHandler::PLACEHOLDER]." AND 
                    BO.".NamedEntity::CODE." = ".$object_field[ISqlHandler::PLACEHOLDER]." AND 
                    S.".IEntity::ID." = ".$sid_field[ISqlHandler::PLACEHOLDER]." AND 
                    U.".IEntity::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND 
                    BO.".IEntity::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND 
                    BP.".IEntity::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND 
                    R.".IEntity::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND
                    S.".IEntity::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]."
            ";
            var_dump("SQL",$arguments[ISqlHandler::QUERY_TEXT]);
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

        public function getAllow(array $args): string
        {
            $result = Common::setIfExists(self::IS_ALLOW,$args,ICommon::EMPTY_VALUE);
            return $result;
        }

        public function set(string $object,string $action,string $sessionId): array
        {
            $result = ICommon::EMPTY_ARRAY;
            $result[self::OBJECT] = Common::isSetEx($object,ICommon::EMPTY_VALUE);
            $result[self::ACTION] = Common::isSetEx($action,ICommon::EMPTY_VALUE);
            $result[self::SESSION_ID] = Common::isSetEx($sessionId,ICommon::EMPTY_VALUE);
            return $result;
        }
    }
}