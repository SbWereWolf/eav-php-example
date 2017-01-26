<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 20.01.17
 * Time: 16:56
 */
namespace Assay\Permission\InterfacePermission {

    use Assay\Core\Common;
    use Assay\Core\Entity;
    use Assay\Core\NamedEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;
    use Assay\Permission\Privilege\BusinessObject;
    use Assay\Permission\Privilege\BusinessProcess;
    use Assay\Permission\Privilege\BusinessRole;
    use Assay\Permission\Privilege\ObjectPrivilege;
    use Assay\Permission\Privilege\RoleDetail;
    use Assay\Permission\Privilege\Session;
    use Assay\Permission\Privilege\Account;
    use Assay\Permission\Privilege\AccountRole;

    Class Permission extends Entity implements IPermission {
        public function checkPrivilege(array $args): array
        {
            $result[self::IS_ALLOW] = self::EMPTY_ARRAY;
            $object = Common::isSetEx($args[self::OBJECT],self::EMPTY_VALUE);
            $action = Common::isSetEx($args[self::ACTION],self::EMPTY_VALUE);
            $sessionId = Common::isSetEx($args[self::SESSION_ID],self::EMPTY_VALUE);

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
            $is_hidden_field[ISqlHandler::VALUE] = self::DEFINE_AS_NOT_HIDDEN;
            $is_hidden_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            $arguments[ISqlHandler::QUERY_TEXT] = "
                SELECT 
                    NULL 
                FROM 
                    ".Session::TABLE_NAME." S 
                JOIN 
                    ".Account::TABLE_NAME." U ON S.".Account::EXTERNAL_ID." = U.".self::ID."
                JOIN 
                    ".AccountRole::TABLE_NAME." UR ON U.".self::ID." = UR.".Account::EXTERNAL_ID."
                JOIN 
                    ".BusinessRole::TABLE_NAME." R ON R.".self::ID." = UR.".BusinessRole::EXTERNAL_ID."
                JOIN 
                    ".RoleDetail::TABLE_NAME." RD ON RD.".BusinessRole::EXTERNAL_ID." = R.".self::ID." 
                JOIN 
                    ".ObjectPrivilege::TABLE_NAME." P ON P.".self::ID." = RD.".ObjectPrivilege::EXTERNAL_ID."
                JOIN 
                    ".BusinessProcess::TABLE_NAME." BP ON BP.".self::ID." = P.".BusinessProcess::EXTERNAL_ID."
                JOIN 
                    ".BusinessObject::TABLE_NAME." BO ON BO.".self::ID." = P.".BusinessObject::EXTERNAL_ID."
                WHERE
                    BP.".NamedEntity::CODE." = ".$process_field[ISqlHandler::PLACEHOLDER]." AND 
                    BO.".NamedEntity::CODE." = ".$object_field[ISqlHandler::PLACEHOLDER]." AND 
                    S.".self::ID." = ".$sid_field[ISqlHandler::PLACEHOLDER]." AND 
                    U.".self::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND 
                    BO.".self::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND 
                    BP.".self::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND 
                    R.".self::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]." AND
                    S.".self::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::PLACEHOLDER]."
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

        public function getAllow(array $args): string
        {
            $result = Common::setIfExists(self::IS_ALLOW,$args,self::EMPTY_VALUE);
            return $result;
        }

        public function getGreetingsRole(): string
        {
            $session = new Session();
            $storedSession = $session->loadByKey();
            $session->id = $storedSession[self::ID];
            $session->getStored();
            $result = $session->greetingsRole;
            return $result;
        }

        public function getMode(): string
        {
            $session = new Session();
            $storedSession = $session->loadByKey();
            $session->id = $storedSession[self::ID];
            $session->getStored();
            $result = $session->mode;
            return $result;
        }

        public function getProfileId(): string
        {
            $session = new Session();
            $storedSession = $session->loadByKey();
            $session->id = $storedSession[self::ID];
            $session->getStored();
            $result = $session->profileId;
            return $result;
        }

        public function getUserId(): string
        {
            $session = new Session();
            $storedSession = $session->loadByKey();
            $session->id = $storedSession[self::ID];
            $session->getStored();
            $result = $session->userId;
            return $result;
        }

        public function getPaging(): string
        {
            $session = new Session();
            $storedSession = $session->loadByKey();
            $session->id = $storedSession[self::ID];
            $session->getStored();
            $result = $session->paging;
            return $result;
        }

        public function set(string $object,string $action,string $sessionId): array
        {
            $result = self::EMPTY_ARRAY;
            $result[self::OBJECT] = Common::isSetEx($object,self::EMPTY_VALUE);
            $result[self::ACTION] = Common::isSetEx($action,self::EMPTY_VALUE);
            $result[self::SESSION_ID] = Common::isSetEx($sessionId,self::EMPTY_VALUE);
            return $result;
        }
    }
}