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
    use Assay\Core\INamedEntity;
    use Assay\Core\NamedEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    class AccountRole extends Entity implements IAccountRole, IAuthorizeProcess
    {
        /** @var string название таблицы */
        const TABLE_NAME = 'account_role';

        /** @var string ссылка на учётную запись */
        public $userId;
        public $userRole;

        public function __construct(string $userId)
        {
            $this->userId = $userId;
            $this->tablename = self::TABLE_NAME;
        }

        public function grantRole(string $role):bool
        {
            $result = false;

            /*
            $user_id[ISqlHandler::PLACEHOLDER] = ':USER_ID';
            $user_id[ISqlHandler::VALUE] = $this->userId;
            $user_id[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            $role_field[ISqlHandler::PLACEHOLDER] = ':USER_ROLE_ID';
            $role_field[ISqlHandler::VALUE] = $role;
            $role_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            */

            $accountIdField = SqlHandler::setBindParameter(':ACCOUNT_ID',$this->userId,\PDO::PARAM_STR);
            $roleField = SqlHandler::setBindParameter(':ACCOUNT_ROLE_ID',$role,\PDO::PARAM_STR);

            $arguments[ISqlHandler::QUERY_TEXT] = '
                INSERT INTO 
                    '.$this->tablename.'
                (
                    '.IAccount::EXTERNAL_ID.', '.IAccountRole::ROLE.'
                ) 
                SELECT
                    '.$accountIdField[ISqlHandler::PLACEHOLDER].','.self::ID.'
                FROM
                    '.BusinessRole::TABLE_NAME.'
                WHERE
                    '.INamedEntity::CODE.' = '.$roleField[ISqlHandler::PLACEHOLDER].'
                RETURNING 
                    '.IAccount::EXTERNAL_ID.','.IAccountRole::ROLE.'
            ';
            $arguments[ISqlHandler::QUERY_PARAMETER] = [
                $accountIdField,
                $roleField
            ];
            $record = SqlHandler::writeOneRecord($arguments);

            $result = $record != ISqlHandler::EMPTY_ARRAY;

            /*
            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);
            $result = SqlHandler::isNoError($response);
            */

            return $result;
        }

        public function revokeRole(string $role):bool
        {
            $result = false;

            /*
            $user_id[ISqlHandler::PLACEHOLDER] = ':ACCOUNT_ID';
            $user_id[ISqlHandler::VALUE] = $this->userId;
            $user_id[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            $role_field[ISqlHandler::PLACEHOLDER] = ':ACCOUNT_ROLE_ID';
            $role_field[ISqlHandler::VALUE] = $role;
            $role_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            */

            $accountIdField = SqlHandler::setBindParameter(':ACCOUNT_ID',$this->userId,\PDO::PARAM_STR);
            $roleField = SqlHandler::setBindParameter(':ACCOUNT_ROLE_ID',$role,\PDO::PARAM_STR);

            $arguments[ISqlHandler::QUERY_TEXT] = '
                DELETE FROM
                    '.$this->tablename.'
                WHERE 
                    '.IAccount::EXTERNAL_ID.' = '.$accountIdField[ISqlHandler::PLACEHOLDER].' AND 
                    '.IAccountRole::ROLE.' = (
                        SELECT 
                            '.self::ID.' 
                        FROM 
                            '.BusinessRole::TABLE_NAME.' 
                        WHERE 
                            '.NamedEntity::CODE.' = '.$roleField[ISqlHandler::PLACEHOLDER].'
                    ) 
                RETURNING 
                    NULL
            ';
            $arguments[ISqlHandler::QUERY_PARAMETER] = [
                $accountIdField,
                $roleField
            ];

            $record = SqlHandler::writeOneRecord($arguments);

            $result = $record != ISqlHandler::EMPTY_ARRAY;

            /*
            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);
            $result = SqlHandler::isNoError($response);
            */
            return $result;
        }

        public function userAuthorization(string $process, string $object, string $sid):bool
        {
            $result = false;
            /*
            $process_field[ISqlHandler::PLACEHOLDER] = ':PROCESS';
            $process_field[ISqlHandler::VALUE] = $process;
            $process_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            $object_field[ISqlHandler::PLACEHOLDER] = ':OBJECT';
            $object_field[ISqlHandler::VALUE] = $object;
            $object_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_STR;
            $sid_field[ISqlHandler::PLACEHOLDER] = ':ACCOUNT_ID';
            $sid_field[ISqlHandler::VALUE] = $this->userId;
            $sid_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            $is_hidden_field[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden_field[ISqlHandler::VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            */

            $processField = SqlHandler::setBindParameter(':PROCESS',$process,\PDO::PARAM_STR);
            $objectField = SqlHandler::setBindParameter(':OBJECT',$object,\PDO::PARAM_STR);
            $sidField = SqlHandler::setBindParameter(':SESSION_ID',$this->userId,\PDO::PARAM_STR);
            $isHiddenField = SqlHandler::setBindParameter(':IS_HIDDEN',self::DEFAULT_IS_HIDDEN,\PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] = "
                SELECT 
                    NULL 
                FROM 
                    ".Session::TABLE_NAME." S 
                JOIN 
                    ".Account::TABLE_NAME." U ON S.".Account::EXTERNAL_ID." = U.".self::ID."
                JOIN 
                    ".self::TABLE_NAME." UR ON U.".self::ID." = UR.".Account::EXTERNAL_ID."
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
                    BP.".NamedEntity::CODE." = ".$processField[ISqlHandler::PLACEHOLDER]." AND 
                    BO.".NamedEntity::CODE." = ".$objectField[ISqlHandler::PLACEHOLDER]." AND 
                    S.".self::ID." = ".$sidField[ISqlHandler::PLACEHOLDER]." AND 
                    U.".self::IS_HIDDEN." = ".$isHiddenField[ISqlHandler::PLACEHOLDER]." AND 
                    BO.".self::IS_HIDDEN." = ".$isHiddenField[ISqlHandler::PLACEHOLDER]." AND 
                    BP.".self::IS_HIDDEN." = ".$isHiddenField[ISqlHandler::PLACEHOLDER]." AND 
                    R.".self::IS_HIDDEN." = ".$isHiddenField[ISqlHandler::PLACEHOLDER]." AND
                    S.".self::IS_HIDDEN." = ".$isHiddenField[ISqlHandler::PLACEHOLDER]."
                LIMIT 1
            ";
            $arguments[ISqlHandler::QUERY_PARAMETER] = [
                $processField,
                $objectField,
                $sidField,
                $isHiddenField
            ];
            $record = SqlHandler::readOneRecord($arguments);

            /*
            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $isSuccessfulRead = SqlHandler::isNoError($response);
            */

            $result = $record != Common::EMPTY_ARRAY;

            return $result;
        }
    }

}